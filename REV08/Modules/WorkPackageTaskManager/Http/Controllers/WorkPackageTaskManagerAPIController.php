<?php

namespace Modules\WorkPackageTaskManager\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Routing\Controller;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\FreelancerOffer\Entities\FreelancerOffer;
use Modules\WorkPackageManager\Entities\WorkPackage;
use Modules\WorkPackageManager\Entities\WorkPackageChat;
use Modules\WorkPackageTaskManager\Entities\TaskChat;
use Modules\WorkPackageTaskManager\Entities\WorkPackageActivity;
use Modules\WorkPackageTaskManager\Entities\WorkPackageCategory;
use Modules\WorkPackageTaskManager\Entities\WorkPackageProgress;
use Modules\WorkPackageTaskManager\Entities\WorkPackageTask;
use Modules\WorkPackageTaskManager\Http\Requests\WorkPackageProgressRequest;

class WorkPackageTaskManagerAPIController extends Controller
{
    public function show($id)
    {
        /* Get Activity */
        $Data['workPackage'] = WorkPackage::select('id', 'package_number', 'title', 'attachment_for_all', 'attachment_for_winner', 'desc', 'status')
            ->where('id', $id)
            ->whereIn('status', ['activated', 'completed'])
            ->first();
        $Activity = WorkPackageActivity::where('work_package_id', $id)->get();
        $freelancerOffer = FreelancerOffer::where('work_package_id', $id)->where('user_id', auth('sanctum')->user()->id)->where('status', 'winner')->count();
        $ChatCount = WorkPackageChat::where('work_package_id', $id)->where('type', 'on_board')->where('replay_to_user_id', '!=', null)->where('viewed', 0)->count();

        if ($Data['workPackage']) {
            if ($Data['workPackage']->attachment_for_all) {
                $WorkPackage['attachment_for_all'] = [
                    'file_name' => HomeController::GetFileName($Data['workPackage']->attachment_for_all),
                    'path' => HomeController::GetFilePath($Data['workPackage']->attachment_for_all)
                ];
            }
            if ($Data['workPackage']->attachment_for_winner) {
                $WorkPackage['attachment_for_winner'] = [
                    'file_name' => HomeController::GetFileName($Data['workPackage']->attachment_for_winner),
                    'path' => HomeController::GetFilePath($Data['workPackage']->attachment_for_winner)
                ];
            }
        }


        /* Get Category */
        if ($Data['workPackage'] && $Activity && $freelancerOffer > 0) {
            $count['a'] = count($Activity);
            $count['c'] = 0;
            foreach ($Activity as $keyActivity => $itemActivity) {
                $category = null;
                $category = WorkPackageCategory::where('activity_id', $itemActivity->id)->get();
                if (isset($category) && count($category)) {
                    if ($count['c'] <= count($category)) {
                        $count['c'] = count($category);
                    }
                    $Activity[$keyActivity]['category'] = $category;

                    $isActive = 0;
                    foreach ($Activity[$keyActivity]['category'] as $keyCategory => $itemCategory) {
                        if ($keyCategory == 0 && $itemCategory->status !== 'completed') {
                            $Activity[$keyActivity]['category'][$keyCategory]['status'] = 'active';
                        }
                        if ($itemCategory->status === 'completed') {
                            $Activity[$keyActivity]['category'][$keyCategory]['status'] = 'completed';
                            $isActive = 1;
                        }
                        if ($isActive && $itemCategory->status !== 'completed') {
                            $Activity[$keyActivity]['category'][$keyCategory]['status'] = 'active';
                            $isActive = 0;
                        }
                    }
                }
            }

            $Data['Activity'] = $Activity;
            $Data['Count'] = $count;
            $Data['ChatCount'] = $ChatCount;
            $Data['WorkPackageFile'] = isset($WorkPackage) ? $WorkPackage : null;
            return response()->json(['getData' => $Data]);
        } else {
            return response()->json(['getData' => 'no_permission']);
        }
    }

    public
    function taskShow($id)
    {
        $Data['category'] = WorkPackageCategory::find($id);
        $Data['taskList'] = WorkPackageTask::where('category_id', $id)->get();
        $Data['workPackage'] = WorkPackage::select('id', 'package_number', 'title')->find($Data['category']->work_package_id);

        if (FreelancerOffer::where('work_package_id', $Data['category']->work_package_id)->where('user_id', auth('sanctum')->user()->id)->where('status', 'winner')->count()) {
            foreach ($Data['taskList'] as $key => $item) {
                $Data['taskList'][$key]['inProgress'] = WorkPackageProgress::where('task_id', $item->id)->where('status', 'new')->count();
                $Data['taskList'][$key]['chat'] = TaskChat::where('task_id', $item->id)->where('status', 'new')->where('users_id', '!=', auth('sanctum')->user()->id)->count();
            }
            return response()->json(['getData' => $Data]);
        } else {
            return response()->json(['getData' => 'no_permission']);
        }
    }

    public
    function taskDetails($id)
    {
        $Data['task'] = WorkPackageTask::find($id);
        $Data['category'] = WorkPackageCategory::find($Data['task']->category_id);
        $Data['workPackage'] = WorkPackage::select('id', 'package_number', 'title')->find($Data['task']->work_package_id);

        if (FreelancerOffer::where('work_package_id', $Data['task']->work_package_id)->where('user_id', auth('sanctum')->user()->id)->where('status', 'winner')->count()) {
            return response()->json(['getData' => $Data]);
        } else {
            return response()->json(['getData' => 'no_permission']);
        }
    }

    public
    function progressList($id)
    {
        $Data['task'] = WorkPackageTask::find($id);
        $Data['workPackage'] = WorkPackage::select('id', 'package_number', 'title')->find($Data['task']->work_package_id);
        $Data['category'] = WorkPackageCategory::find($Data['task']->category_id);
        $Data['progress'] = WorkPackageProgress::where('task_id', $id)->get();

        foreach ($Data['progress'] as $itemProgress) {
            $itemProgress['attachment'] = [
                'name' => HomeController::GetFileName($itemProgress->attachment),
                'path' => HomeController::GetFilePath($itemProgress->attachment)
            ];
        }

        return response()->json(['getData' => $Data]);

    }

    public function progressStore(WorkPackageProgressRequest $request, $id)
    {
        $progress = new WorkPackageProgress();

        $progress->forceFill([
            'task_id' => $id,
            'user_id' => auth('sanctum')->id(),
            'status' => 'new',
        ]);

        if ($request->hasFile('attachment')) {
            $progress->attachment = FileLibraryController::upload(
                $request->file('attachment'),
                'file',
                'work-package/task/progress',
                'task progress'
            );
        } else {
            $progress->attachment = 0;
        }

        $progress->save();

        WorkPackageTask::find($id)->update(['status' => null]);

        return response()->json(['status' => 200]);
    }
}
