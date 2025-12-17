<?php

namespace Modules\WorkPackageTaskManager\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\FreelancerOffer\Entities\FreelancerOffer;
use Modules\WorkPackageManager\Entities\WorkPackage;
use Modules\WorkPackageTaskManager\Entities\TaskChat;
use Modules\WorkPackageTaskManager\Entities\WorkPackageTask;

class TaskChatAPIController extends Controller
{
    public function get($id)
    {
        $chatList = TaskChat::with('users_tbl')->where('task_id', $id)->get();
        $task = WorkPackageTask::find($id);
        $workPackage = WorkPackage::select('id', 'package_number', 'title')->find($task->work_package_id);
        $freelancerOffer = FreelancerOffer::where('user_id', auth('sanctum')->user()->id)->where('work_package_id', $task->work_package_id)->where('status', 'winner')->count();
        $Data['chatList'] = $chatList;
        $Data['task'] = $task;
        $Data['workPackage'] = $workPackage;

        if ($freelancerOffer) {
            foreach ($chatList as $key => $item) {
                if ($item->users_id != auth('sanctum')->user()->id) {
                    $item->update(['status' => 'viewed']);
                }

                if ($item->attachment) {
                    $item['attachment'] = [
                        'name' => HomeController::GetFileName($item->attachment),
                        'path' => HomeController::GetFilePath($item->attachment)
                    ];
                }
            }
            return response()->json(['getData' => $Data]);
        } else {
            return response()->json(['getData' => 'no_permission']);
        }
    }

    public function store(Request $request, $id)
    {
        $data = $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,webp,pdf,doc,docx'
        ]);

        $data['users_id'] = auth('sanctum')->id();
        $data['task_id'] = $id;
        $data['status'] = 'new';

        if ($request->hasFile('attachment')) {
            $data['attachment'] = FileLibraryController::upload(
                $request->file('attachment'),
                'file',
                'work-package/task/chat',
                'task chat'
            );
        }

        TaskChat::create($data);

        return response()->json(['status' => 200]);
    }
}
