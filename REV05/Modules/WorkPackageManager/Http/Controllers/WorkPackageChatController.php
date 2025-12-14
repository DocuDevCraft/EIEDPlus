<?php

namespace Modules\WorkPackageManager\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\SmsHandler\Http\Controllers\SmsHandlerController;
use Modules\WorkPackageManager\Entities\WorkPackage;
use Modules\WorkPackageManager\Entities\WorkPackageChat;
use Modules\WorkPackageManager\Http\Requests\WorkPackageChatRequest;

class WorkPackageChatController extends Controller
{
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {

        $chatList = WorkPackageChat::where('work_package_id', $id)->where('replay_to_user_id', null)->when(WorkPackage::find($id)->wp_activation_time, function ($query) {
            $query->where('type', 'on_board');
        })->orderBy('created_at', 'DESC')->get();

        if ($chatList) {
            foreach ($chatList as $key => $item) {
                $Parent = '';
                $Parent = WorkPackageChat::where('replay_to_user_id', $item->id)->where('status', 'published')->get();

                if ($Parent) {
                    $chatList[$key]['parent'] = $Parent;
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
            'chatList'
        ];

        return view('workpackagemanager::chat', compact($Data));
    }

    public function store(WorkPackageChatRequest $request, $id)
    {
        $WorkPackage = WorkPackage::select('id', 'title')->find($id);
        $WorkPackageChat = WorkPackageChat::find($request->replay_to_user_id);

        $Data = $request->all();
        $Data['work_package_id'] = $id;
        $Data['user_id'] = auth()->user()->id;
        $Data['type'] = $WorkPackageChat->type;
        $Data['status'] = 'published';

        if (WorkPackageChat::create($Data)) {
            $WorkPackageChat->update(['status' => 'published']);
            SmsHandlerController::Send([$WorkPackageChat->users->phone], "یک پیام در بخش سوالات عمومی بسته کاری «{$WorkPackage->title}» ثبت شد.");

            return redirect()->back()->with('notification', [
                'class' => 'success',
                'message' => 'پاسخ ارسال شد.'
            ]);
        }
    }
}
