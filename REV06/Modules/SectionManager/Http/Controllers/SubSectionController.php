<?php

namespace Modules\SectionManager\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Freelancer\Entities\FreelancerHourlyContract;
use Modules\Freelancer\Entities\FreelancerSection;
use Modules\SectionManager\Entities\Section;
use Modules\SectionManager\Entities\Subsection;
use Modules\SectionManager\Http\Requests\SubsectionRequest;
use Modules\Users\Entities\Users;

class SubSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $SubSection = Subsection::paginate(10);
        $Section = Section::get()->all();
        $SectionManager = Users::where('role', 'workPackageManager')->select('id', 'first_name', 'last_name')->get()->all();
        $Appraiser = Users::where('role', 'appraiser')->select('id', 'first_name', 'last_name')->get()->all();

        $Data = [
            'SubSection',
            'Section',
            'SectionManager',
            'Appraiser',
        ];

        return view('sectionmanager::subsection.index', compact($Data));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(SubsectionRequest $request)
    {
        $SubSectionData['title'] = $request->title;
        $SubSectionData['section_id'] = $request->section;

        if ($SubSection = Subsection::create($SubSectionData)) {
            $SubSection->Users()->attach($request->manager);
            $SubSection->Appraiser()->attach($request->appraiser);

            return redirect()->back()->with('notification', [
                'class' => 'success',
                'message' => 'زیر بخش ایجاد شد.'
            ]);
        } else {
            return redirect()->back()->with('notification', [
                'class' => 'alert',
                'message' => 'زیر بخش ایجاد نشد'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $Subsection = Subsection::find($id);
        $Section = Section::get()->all();
        $SectionManager = Users::where('role', 'workPackageManager')->select('id', 'first_name', 'last_name')->get()->all();
        $Appraiser = Users::where('role', 'appraiser')->select('id', 'first_name', 'last_name')->get()->all();

        $Data = [
            'Subsection',
            'Section',
            'SectionManager',
            'Appraiser',
        ];

        return view('sectionmanager::subsection.edit', compact($Data));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(SubsectionRequest $request, $id)
    {
        $Subsection = Subsection::find($id);
        $SubsectionData['title'] = $request->title;
        $SubsectionData['section_id'] = $request->section;

        if ($Subsection->update($SubsectionData)) {
            $Subsection->Users()->sync($request->manager);
            $Subsection->Appraiser()->sync($request->appraiser);

            return redirect()->back()->with('notification', [
                'class' => 'success',
                'message' => 'زیر بخش بروزرسانی شد.'
            ]);
        } else {
            return redirect()->back()->with('notification', [
                'class' => 'alert',
                'message' => 'زیر بخش ویرایش نشد'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        foreach ($request->delete_item as $key => $item) {
            /* Resume Delete */
            Subsection::where('id', $key)->delete();
        }

        return redirect('/dashboard/subsection')->with('notification', [
            'class' => 'success',
            'message' => 'موارد حذف شد'
        ]);
    }

    public function Check($value)
    {
        $Subsection = Subsection::where('section_id', $value)->select('id', 'title')->get()->all();
        $NormallyFreelancers = FreelancerSection::where('section_id', $value)
            ->with(['freelancer.users:id,first_name,last_name'])
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->freelancer->id,
                    'user_id' => $item->freelancer->users_id,
                    'first_name' => $item->freelancer->users->first_name,
                    'last_name' => $item->freelancer->users->last_name,
                ];
            })
            ->unique('id')
            ->values();

        $HourlyFreelancers = $NormallyFreelancers->filter(function ($freelancer) {
            return FreelancerHourlyContract::where('user_id', $freelancer['user_id'])
                ->where('status', 'freelancer_signed')
                ->exists();
        })->values();

        return response()->json(['subsection' => $Subsection, 'normally_freelancer' => $NormallyFreelancers, 'freelancers' => $HourlyFreelancers]);
    }
}
