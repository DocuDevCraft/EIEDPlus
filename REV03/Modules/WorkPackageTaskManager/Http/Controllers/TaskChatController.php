<?php

namespace Modules\WorkPackageTaskManager\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Routing\Controller;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\FreelancerOffer\Entities\FreelancerOffer;
use Modules\SmsHandler\Http\Controllers\SmsHandlerController;
use Modules\WorkPackageManager\Entities\WorkPackage;
use Modules\WorkPackageManager\Http\Requests\WorkPackageChatRequest;
use Modules\WorkPackageTaskManager\Entities\TaskChat;
use Modules\WorkPackageTaskManager\Entities\WorkPackageTask;

class TaskChatController extends Controller
{
    public function taskComment($id)
    {
        $ID = $id;
        $chatList = TaskChat::where('task_id', $id)->get();

        $WorkPackageID = WorkPackageTask::find($id)->work_package_id;

        if ($chatList) {
            foreach ($chatList as $key => $item) {
                if ($item->users_id != auth()->user()->id) {
                    $item->update(['status' => 'viewed']);
                }

                if ($item->attachment) {
                    $item['attachment'] = [
                        'file_name' => HomeController::GetFileName($item->attachment),
                        'path' => HomeController::GetFilePath($item->attachment)
                    ];
                }
            }
        }

        $Data = [
            'ID',
            'chatList',
            'WorkPackageID'
        ];

        return view('workpackagetaskmanager::task-chat', compact($Data));
    }


    public function taskCommentStore(WorkPackageChatRequest $request, $id)
    {
        $Data = $request->all();


        $Data['task_id'] = $id;
        $Data['users_id'] = auth()->user()->id;
        $Data['status'] = 'new';


        if ($request->file('attachment')) {
            $Data['attachment'] = FileLibraryController::upload($request->file('attachment'), 'file', 'work-package/task/chat', 'task chat');
        }

        if (TaskChat::create($Data)) {
            $WorkPackageTask = WorkPackageTask::find($id);
            $WorkPackage = WorkPackage::find($WorkPackageTask->work_package_id);
            $FreelancerPhone = FreelancerOffer::where('work_package_id', $WorkPackageTask->work_package_id)->where('status', 'winner')->first()->users->phone;
            SmsHandlerController::Send([$FreelancerPhone], " یک پیام در بخش ITC بسته کاری «{$WorkPackage->title}» برای وظیفه «{$WorkPackageTask->title}» ثبت شد.");

            return redirect()->back()->with('notification', [
                'class' => 'success',
                'message' => 'پاسخ ارسال شد.'
            ]);
        }
    }

}
