<?php

namespace Modules\WorkPackageTaskManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\WorkPackageTaskManager\Entities\WorkPackageActivity;
use Modules\WorkPackageTaskManager\Entities\WorkPackageCategory;
use Modules\WorkPackageTaskManager\Entities\WorkPackageTask;
use Modules\WorkPackageTaskManager\ImportsExports\ActivityExport;
use Modules\WorkPackageTaskManager\ImportsExports\ActivityImport;
use Modules\WorkPackageTaskManager\Services\WorkPackageStorageService;

class WorkPackageTaskManagerController extends Controller
{
    public function show($id)
    {
        $ID = $id;
        /* Get Activity */
        $Activity = WorkPackageActivity::where('work_package_id', $id)->get();

        /* Get Category */
        if ($Activity) {
            foreach ($Activity as $keyCategory => $itemCategory) {
                $category = null;
                $category = WorkPackageCategory::where('activity_id', $itemCategory->id)->get();
                if (isset($category) && count($category)) {
                    $Activity[$keyCategory]['category'] = $category;

                    foreach ($Activity[$keyCategory]['category'] as $keyTask => $itemTask) {
                        $task = null;
                        $task = WorkPackageTask::where('category_id', $itemTask->id)->get();
                        if (isset($task) && count($task)) {
                            $Activity[$keyCategory]['category'][$keyTask]['task'] = $task;
                        }
                    }
                }
            }
        }

        $Data = [
            'ID',
            'Activity'
        ];

        return view('workpackagetaskmanager::activity', compact($Data));
    }


    public function store(Request $request, $id, WorkPackageStorageService $service)
    {
        $validated = $request->validate(
            ['activity_list.*.category_list.*.due_date' => 'required'],
            [],
            ['activity_list.*.category_list.*.due_date' => 'تاریخ سر رسید الزامی است']
        );

        try {
            $service->store($id, $validated);

            return back()->with('notification', [
                'class' => 'success',
                'message' => 'وظایف بسته کاری بروز رسانی شد.'
            ]);
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('form_debug', $validated)
                ->with('notification', [
                    'class' => 'alert',
                    'message' => $e->getMessage(),
                ]);
        }
    }

    public function exportActivity($id)
    {
        return Excel::download(new ActivityExport($id), 'work_package_blocks_import.xlsx');
    }

    public function importActivity(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv'
        ]);

        Excel::import(new ActivityImport($id), $request->file('file'));

        return redirect()->back()->with('notification', [
            'class' => 'success',
            'message' => 'وظایف بسته کاری بروز رسانی شد.'
        ]);
    }
}
