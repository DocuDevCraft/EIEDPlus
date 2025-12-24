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
        $data = $request->validated();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = FileLibraryController::upload(
                $request->file('attachment'),
                'file',
                'work-package/task/chat',
                'task chat'
            );
        }

        $chat = new TaskChat();

        // فقط فیلدهای مجاز از سمت کاربر
        $chat->fill([
            'message' => $data['message'],
            'attachment' => $data['attachment'] ?? null,
        ]);

        // فیلدهای سیستمی و non-nullable
        $chat->forceFill([
            'task_id' => $id,
            'users_id' => auth()->id(),
            'status' => 'new',
        ])->save();

        $task = WorkPackageTask::findOrFail($id);
        $package = WorkPackage::find($task->work_package_id);

        $phone = FreelancerOffer::where('work_package_id', $task->work_package_id)
            ->where('status', 'winner')
            ->first()?->users?->phone;

        if ($phone) {
            SmsHandlerController::Send(
                [$phone],
                "یک پیام در بخش ITC بسته کاری «{$package->title}» برای وظیفه «{$task->title}» ثبت شد."
            );
        }

        return back()->with('notification', [
            'class' => 'success',
            'message' => 'پاسخ ارسال شد.'
        ]);
    }

}
