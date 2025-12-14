<?php

namespace Modules\SectionManager\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SectionManager\Entities\Section;
use Modules\SectionManager\Entities\SectionManager;
use Modules\SectionManager\Http\Requests\SectionManagerRequest;
use Modules\Users\Entities\Users;

class SectionManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $Section = Section::paginate(10);
        $SectionManager = Users::where('role', 'sectionManager')->select('id', 'first_name', 'last_name')->get()->all();
        $ChiefAppraiser = Users::where('role', 'chiefAppraiser')->select('id', 'first_name', 'last_name')->get()->all();

        $Data = [
            'Section',
            'SectionManager',
            'ChiefAppraiser',
        ];

        return view('sectionmanager::section.index', compact($Data));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(SectionManagerRequest $request)
    {
        $SectionData['title'] = $request->title;
        $SectionData['code'] = $request->code ? strtolower($request->code) : $Section->code;

        if ($Section = Section::create($SectionData)) {
            $Section->Users()->attach($request->manager);
            $Section->ChiefAppraiser()->attach($request->chiefAppraiser);

            return redirect()->back()->with('notification', [
                'class' => 'success',
                'message' => 'بخش ایجاد شد.'
            ]);
        } else {
            return redirect()->back()->with('notification', [
                'class' => 'alert',
                'message' => 'بخش ایجاد نشد.'
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
        $Section = Section::find($id);
        $SectionManager = Users::where('role', 'sectionManager')->select('id', 'first_name', 'last_name')->get()->all();
        $ChiefAppraiser = Users::where('role', 'chiefAppraiser')->select('id', 'first_name', 'last_name')->get()->all();

        $Data = [
            'Section',
            'SectionManager',
            'ChiefAppraiser',
        ];

        return view('sectionmanager::section.edit', compact($Data));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(SectionManagerRequest $request, $id)
    {
        $Section = Section::find($id);
        $SectionData['title'] = $request->title;
        $SectionData['code'] = $request->code ? strtolower($request->code) : $Section->code;

        if ($Section->update($SectionData)) {
            $Section->Users()->sync($request->manager);
            $Section->ChiefAppraiser()->sync($request->chiefAppraiser);

            return redirect()->back()->with('notification', [
                'class' => 'success',
                'message' => 'بخش بروزرسانی شد.'
            ]);
        } else {
            return redirect()->back()->with('notification', [
                'class' => 'alert',
                'message' => 'بخش ویرایش نشد'
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
            Section::where('id', $key)->delete();
        }

        return redirect('/dashboard/section')->with('notification', [
            'class' => 'success',
            'message' => 'موارد حذف شد'
        ]);
    }
}
