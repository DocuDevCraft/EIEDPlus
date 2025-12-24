<?php

namespace Modules\SectionManager\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Freelancer\Entities\FreelancerHourlyContract;
use Modules\Freelancer\Entities\FreelancerSection;
use Modules\SectionManager\Entities\Division;
use Modules\SectionManager\Entities\Subsection;
use Modules\SectionManager\Http\Requests\DivisionRequest;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $Division = Division::paginate(20);
        $Subsection = Subsection::get()->all();

        $Data = [
            'Division',
            'Subsection',
        ];

        return view('sectionmanager::division.index', compact($Data));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(DivisionRequest $request)
    {
        $DivisionData['title'] = $request->title;
        $DivisionData['subsection_id'] = $request->subsection;

        $division = Division::create($DivisionData);

        // users_id چون fillable نیست
        $division->forceFill([
            'users_id' => auth()->user()->id,
        ])->save();

        return redirect()->back()->with('notification', [
            'class' => 'success',
            'message' => 'قسمت ایجاد شد.'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $Division = Division::find($id);
        $Subsection = Subsection::get()->all();

        $Data = [
            'Division',
            'Subsection',
        ];

        return view('sectionmanager::division.edit', compact($Data));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(DivisionRequest $request, $id)
    {
        $Division = Division::find($id);

        $DivisionData['title'] = $request->title;
        $DivisionData['subsection_id'] = $request->subsection;

        $Division->update($DivisionData);

        // users_id چون fillable نیست
        $Division->forceFill([
            'users_id' => auth()->user()->id,
        ])->save();

        return redirect()->back()->with('notification', [
            'class' => 'success',
            'message' => 'قسمت بروزرسانی شد.'
        ]);
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
            Division::where('id', $key)->delete();
        }

        return redirect('/dashboard/division')->with('notification', [
            'class' => 'success',
            'message' => 'موارد حذف شد'
        ]);
    }

    public function Check($value)
    {
        $Division = Division::where('subsection_id', $value)->select('id', 'title')->get();
        $NormallyFreelancers = FreelancerSection::where('subsection_id', $value)
            ->with(['freelancer.users:id,first_name,last_name'])
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->freelancer->id,
                    'user_id' => $item->freelancer->users_id, // مهم برای مرحله بعد!
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

        return response()->json(['division' => $Division, 'normally_freelancer' => $NormallyFreelancers, 'freelancers' => $HourlyFreelancers]);
    }
}
