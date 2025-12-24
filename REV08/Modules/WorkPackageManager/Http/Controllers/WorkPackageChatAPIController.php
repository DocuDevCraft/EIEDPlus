<?php

namespace Modules\WorkPackageManager\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Routing\Controller;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\SmsHandler\Http\Controllers\SmsHandlerController;
use Modules\WorkPackageManager\Entities\WorkPackage;
use Modules\WorkPackageManager\Entities\WorkPackageChat;
use Modules\WorkPackageManager\Http\Requests\WorkPackageChatRequest;

class WorkPackageChatAPIController extends Controller
{
    /*
    * Get Work package Chat List
    * Route: /api/work-package/public/chat/{id}
    * GET
    * */
    public function chatList($id)
    {
        $getData = [];
        $getData['details'] = WorkPackage::select('id', 'title', 'desc', 'package_number', 'package_price_type', 'package_time_type', 'attachment_for_all', 'rules', 'status')->find($id);
        $getData['chatList'] = WorkPackageChat::where('status', 'published')->where('type', 'public')->where('work_package_id', $id)->where('replay_to_user_id', null)->get();

        if ($getData['chatList']) {
            foreach ($getData['chatList'] as $key => $item) {
                $Parent = '';
                $Parent = WorkPackageChat::where('replay_to_user_id', $item->id)->where('type', 'public')->where('status', 'published')->get();

                if ($Parent) {
                    $getData['chatList'][$key]['parent'] = $Parent;
                }

                if ($item->attachment) {
                    $item['attachment'] = [
                        'file_name' => HomeController::GetFileName($item->attachment),
                        'path' => HomeController::GetFilePath($item->attachment)
                    ];
                }
            }
        }

        if ($getData['details']) {
            if ($getData['details']->attachment_for_all) {
                $getData['details']['attachment_for_all'] = [
                    'file_name' => HomeController::GetFileName($getData['details']->attachment_for_all),
                    'path' => HomeController::GetFilePath($getData['details']->attachment_for_all)
                ];
            }

            if (!in_array($getData['details']->status, ['new'])) {
                $getData = 'no_permission';
            }
        }

        return response()->json(['getData' => $getData]);
    }

    /*
    * Get Work package Chat List
    * Route: /api/work-package/public/chat/{id}
    * POST
    * */
    public function chatSubmit(WorkPackageChatRequest $request, $id)
    {
        $workPackage = WorkPackage::with('Users')
            ->select('id', 'status', 'uid', 'title', 'users_id')
            ->findOrFail($id);

        if ($workPackage->status !== 'new') {
            return response()->json(['status' => 403, 'message' => 'no_permission']);
        }

        $validated = $request->validated();

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = FileLibraryController::upload(
                $request->file('attachment'),
                'file',
                'work-package/chat',
                'work-package'
            );
        }

        $chat = new WorkPackageChat();

        // فقط داده‌های قابل اعتماد از سمت کاربر
        $chat->fill([
            'message' => $validated['message'],
            'attachment' => $validated['attachment'] ?? null,
            'type' => 'public',
        ]);

        // فیلدهای سیستمی، non-nullable
        $chat->forceFill([
            'work_package_id' => $id,
            'user_id' => auth('sanctum')->id(),
            'status' => 'new',
        ])->save();

        SmsHandlerController::Send(
            [$workPackage->Users->phone],
            "یک سوال در بخش سوالات عمومی بسته کاری «{$workPackage->title}» ارسال شد."
        );

        return response()->json(['status' => 200, 'data' => $chat]);
    }
}
