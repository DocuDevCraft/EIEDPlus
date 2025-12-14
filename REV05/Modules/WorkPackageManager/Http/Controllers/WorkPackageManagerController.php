<?php

namespace Modules\WorkPackageManager\Http\Controllers;

use App\Http\Controllers\HomeController;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\Freelancer\Entities\Freelancer;
use Modules\Freelancer\Entities\FreelancerHourlyContract;
use Modules\SectionManager\Entities\Section;
use Modules\SectionManager\Entities\Subsection;
use Modules\WorkPackageManager\Entities\WorkPackage;
use Modules\WorkPackageManager\Entities\WorkPackageChat;
use Modules\WorkPackageManager\Http\Requests\WorkPackageRequest;
use Morilog\Jalali\Jalalian;

class WorkPackageManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Gate::allows('isAdmin')) {
            $WorkPackage = WorkPackage::with('freelancer_offers')->orderBy('id', 'desc')->where('status', '!=', 'pending')->paginate(20);
        } elseif (Gate::allows('isWorkPackageManager')) {
            $WorkPackage = WorkPackage::where('uid', auth()->user()->id)->orderBy('id', 'desc')->paginate(20);
        } elseif (Gate::allows('isAppraiser')) {
            /* For Appraiser */
            $OwnerSubSection = Subsection::with('Appraiser')->whereHas('Appraiser', function ($query) {
                $query->where('users_id', '=', auth()->user()->id);
            })->pluck('id')->toArray();

            $WorkPackage = WorkPackage::whereIn('subsection_id', $OwnerSubSection ?: [])->where('status', '!=', 'pending')->orderBy('id', 'desc')->paginate(20);
        } elseif (Gate::allows('isChiefAppraiser')) {
            /* For ChiefAppraiser */
            $OwnerSection = Section::with('ChiefAppraiser')->whereHas('ChiefAppraiser', function ($query) {
                $query->where('users_id', '=', auth()->user()->id);
            })->pluck('id')->toArray();
            $WorkPackage = WorkPackage::whereIn('section_id', $OwnerSection ?: [])->orderBy('id', 'desc')->where('status', '!=', 'pending')->paginate(20);
        } elseif (Gate::allows('isSectionManager')) {
            /* For SectionManager */
            $OwnerSection = Section::with('SectionManager')->whereHas('SectionManager', function ($query) {
                $query->where('users_id', '=', auth()->user()->id);
            })->pluck('id')->toArray();
            $WorkPackage = WorkPackage::whereIn('section_id', $OwnerSection ?: [])->orderBy('id', 'desc')->paginate(20);
        } else {
            $WorkPackage = WorkPackage::orderBy('id', 'desc')->paginate(20);
        }

        if ($WorkPackage) {
            foreach ($WorkPackage as $key => $item) {
                if ($item->status === 'awaiting_signature' && $item->wp_activation_time) {
                    $WorkPackage[$key]['status'] = Jalalian::now() > Jalalian::forge($item->wp_activation_time)->addDays(3) ? 'expired' : $item->status;
                }
            }
        }

        $Data = [
            'WorkPackage'
        ];

        return view('workpackagemanager::index', compact($Data));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (!Gate::allows('isWorkPackageManager')) {
            abort(403);
        }

        $Section = Section::get()->all();
        $FreelancerList = FreelancerHourlyContract::where('status', 'freelancer_signed')->get()->all();
        $NormalFreelancerList = Freelancer::get()->all();

        $Data = [
            'Section',
            'FreelancerList',
            'NormalFreelancerList',
        ];
        return view('workpackagemanager::create', compact($Data));


    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(WorkPackageRequest $request)
    {
        if (!Gate::allows('isWorkPackageManager')) {
            abort(403);
        }

        $Data = $request->all();

        if ($request->file('attachment_for_all')) {
            $Data['attachment_for_all'] = FileLibraryController::upload($request->file('attachment_for_all'), 'file', 'work-package/attachment-for-all', 'work-package');
        }

        if ($request->file('attachment_for_winner')) {
            $Data['attachment_for_winner'] = FileLibraryController::upload($request->file('attachment_for_winner'), 'file', 'work-package/attachment-for-winner', 'work-package');
        }

        $Data['uid'] = auth()->user()->id;
        $Data['status'] = 'pending';

        if ($request->tag) {
            $Data['tag'] = json_encode($request->tag);
        }

        $Data['subsection_id'] = is_numeric($request->subsection_id) ? (int)$request->subsection_id : null;
        $Data['division_id'] = is_numeric($request->division_id) ? (int)$request->division_id : null;

        $Data['unique_id'] = 'plus' . "/" . rand(1000, 9999) . '-' . rand(100, 999) . '-' . rand(10, 99);

        $Data['work_package_type'] = $request->work_package_type;

        if ($WorkPackage = WorkPackage::create($Data)) {
            if ($request->work_package_type === 'hourly_contract') {
                $freelancers = $request->input('freelancer', []);
                $WorkPackage->freelancers()->sync($freelancers);
            } elseif ($request->work_package_type === 'public') {
                $freelancers = $request->input('normal_freelancer', []);
                $WorkPackage->freelancers()->sync($freelancers);
            }

            return redirect()->route('work-package.edit', $WorkPackage->id)->with('notification', [
                'class' => 'success',
                'message' => 'بسته کاری ایجاد شد.'
            ]);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('workpackagemanager::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $WorkPackage = WorkPackage::find($id);
        if (Gate::allows('isAdmin') && $WorkPackage->status === 'pending') {
            return abort(403);
        }
        $Section = Section::get()->all();
        $FreelancerList = FreelancerHourlyContract::where('status', 'freelancer_signed')->get()->all();
        $NormalFreelancerList = Freelancer::get()->all();
        $WorkPackageChatList = WorkPackageChat::where('work_package_id', $id)->where('status', 'new')->where('replay_to_user_id', null)->when($WorkPackage->wp_activation_time, function ($query) {
            $query->where('type', 'on_board');
        })->orderBy('created_at', 'DESC')->count();

        if ($WorkPackage->attachment_for_all) {
            $WorkPackage['attachment_for_all'] = [
                'file_name' => HomeController::GetFileName($WorkPackage->attachment_for_all),
                'path' => HomeController::GetFilePath($WorkPackage->attachment_for_all)
            ];
        }
        if ($WorkPackage->attachment_for_winner) {
            $WorkPackage['attachment_for_winner'] = [
                'file_name' => HomeController::GetFileName($WorkPackage->attachment_for_winner),
                'path' => HomeController::GetFilePath($WorkPackage->attachment_for_winner)
            ];
        }


        if (!empty($WorkPackage->published_at)) {
            $publishedCarbon = Carbon::parse($WorkPackage->published_at);

            $WorkPackage->published_at = $publishedCarbon->timestamp;

            if (!empty($WorkPackage->offer_time)) {
                $WorkPackage->offer_time_date = $publishedCarbon
                    ->copy()
                    ->addDays((int)$WorkPackage->offer_time)
                    ->timestamp;
            }
        }

        $Data = [
            'Section',
            'WorkPackage',
            'FreelancerList',
            'NormalFreelancerList',
            'WorkPackageChatList',
        ];

        return view('workpackagemanager::edit', compact($Data));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(WorkPackageRequest $request, $id)
    {
        $workPackage = WorkPackage::findOrFail($id);
        if (Gate::allows('isAdmin') && $workPackage->status === 'pending') {
            return abort(403);
        }
        $data = $request->all();

        $userRole = auth()->user()->role;
        $allowedStatuses = ['pending', 'pre_accept', 'new'];
        $allowedRoles = ['admin', 'sectionManager'];

        $isWorkPackageManager = $workPackage->status === 'pending' && $userRole === 'workPackageManager';
        $isAdminOrManager = in_array($userRole, $allowedRoles) && in_array($workPackage->status, $allowedStatuses);

        if ($isWorkPackageManager || $isAdminOrManager) {
            if ($request->hasFile('attachment_for_all')) {
                $data['attachment_for_all'] = FileLibraryController::upload(
                    $request->file('attachment_for_all'),
                    'file',
                    'work-package/attachment-for-all',
                    'work-package'
                );
            }

            if ($request->hasFile('attachment_for_winner')) {
                $data['attachment_for_winner'] = FileLibraryController::upload(
                    $request->file('attachment_for_winner'),
                    'file',
                    'work-package/attachment-for-winner',
                    'work-package'
                );
            }

            if ($request->filled('tag')) {
                $data['tag'] = json_encode($request->tag);
            }

            $data['division_id'] = is_numeric($request->division_id) ? $request->division_id : null;
            $data['subsection_id'] = $request->subsection_id ?: null;

            if (isset($data['offer_time_date']) && !empty($data['published_at']) && !in_array($workPackage->status, ['pending', 'pre_accept'])) {
                $from = Carbon::createFromTimestamp($data['published_at']);
                $to = Carbon::createFromTimestamp($data['offer_time_date'] ?? time());

                $data['offer_time'] = $from->diffInDays($to);
                $data['published_at'] = $from;
            } else {
                $data['offer_time'] = $workPackage->offer_time;
            }

            $data['work_package_type'] = $request->work_package_type;

            $workPackage->update($data);

            if ($request->work_package_type === 'hourly_contract') {
                $freelancers = $request->input('freelancer', []);
                $workPackage->freelancers()->sync($freelancers);
            } elseif ($request->work_package_type === 'public') {
                $freelancers = $request->input('normal_freelancer', []);
                $workPackage->freelancers()->sync($freelancers);
            }

            return back()->with('notification', [
                'class' => 'success',
                'message' => 'بسته کاری بروزرسانی شد.'
            ]);
        }

        // 🚫 دسترسی غیرمجاز
        return back()->with('notification', [
            'class' => 'error',
            'message' => 'بسته کاری قابل ویرایش نیست.'
        ]);
    }

    public function updateStatus($id)
    {
        $WorkPackage = WorkPackage::find($id);

        if (auth()->user()->role === 'sectionManager') {
            $WorkPackage->update(['status' => 'pre_accept']);
        } elseif (auth()->user()->role === 'admin') {
            $WorkPackage->update([
                'published_at' => Carbon::now(),
                'status' => 'new'
            ]);
        }

        return redirect()->back()->with('notification', [
            'class' => 'success',
            'message' => 'بسته کاری تایید شد.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
//        foreach ($request->delete_item as $key => $item) {
//            /* WorkPackage Delete */
//            WorkPackageManagerChat::where('work_package_id', $key)->delete();
//            WorkPackageChat::where('work_package_id', $key)->delete();
//            FreelancerOffer::where('work_package_id', $key)->delete();
//            $WorkPackageTask = WorkPackageTask::where('work_package_id', $key)->get();
//            if ($WorkPackageTask) {
//                foreach ($WorkPackageTask as $item) {
//                    TaskChat::where('task_id', $item->id)->delete();
//                    WorkPackageProgress::where('task_id', $item->id)->delete();
//                    WorkPackageTask::where('work_package_id', $item->id)->delete();
//                }
//            }
//            WorkPackageActivity::where('work_package_id', $key)->delete();
//            WorkPackageCategory::where('work_package_id', $key)->delete();
//
//            WorkPackage::where('id', $key)->delete();
//        }
//
//        return redirect('/dashboard/users')->with('notification', [
//            'class' => 'success',
//            'message' => 'بسته کاری‌های مورد نظر حذف شد'
//        ]);

    }
}
