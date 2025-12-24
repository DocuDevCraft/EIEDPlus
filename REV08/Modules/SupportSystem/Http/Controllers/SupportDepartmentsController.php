<?php

namespace Modules\SupportSystem\Http\Controllers;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SupportSystem\Entities\SupportDepartments;
use Modules\SupportSystem\Entities\SupportSystem;
use Modules\SupportSystem\Http\Requests\SupportDepartmentsRequest;

class SupportDepartmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $Departments = SupportDepartments::orderBy('created_at', 'desc')->paginate(20);

        return view('supportsystem::departments.index', compact('Departments'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('supportsystem::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(SupportDepartmentsRequest $request)
    {
        $data = $request->validated();

        $slugSource = $data['slug'] ?? $data['title'];
        $data['slug'] = SlugService::createSlug(
            SupportDepartments::class,
            'slug',
            strtolower($slugSource)
        );

        SupportDepartments::create($data);

        return redirect('/dashboard/support-departments')->with('notification', [
            'class' => 'success',
            'message' => 'دپارتمان با موفقیت ایجاد شد'
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('supportsystem::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $SupportDepartments = SupportDepartments::find($id);

        return view('supportsystem::departments.edit', compact('SupportDepartments'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(SupportDepartmentsRequest $request, $id)
    {
        $department = SupportDepartments::findOrFail($id);
        $data = $request->validated();

        if ($department->slug !== $request->slug) {
            $baseSlug = $request->slug ?: $request->title;

            $data['slug'] = SlugService::createSlug(
                SupportDepartments::class,
                'slug',
                strtolower($baseSlug)
            );
        }

        $department->update($data);

        return redirect()->back()->with('notification', [
            'class' => 'success',
            'message' => 'اطلاعات بروزرسانی شد'
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
            $SupportSystem = SupportSystem::where('department', $key)->get()->all();
            foreach ($SupportSystem as $item_inner) {
                $SupportItem = SupportSystem::find($item_inner->id);
                $SupportItem->department = 0;
                $SupportItem->save();
            }
            SupportDepartments::where('id', $key)->delete();
        }

        return redirect('/dashboard/support-departments')->with('notification', [
            'class' => 'success',
            'message' => 'دپارتمان های مورد نظر حذف شد'
        ]);
    }
}
