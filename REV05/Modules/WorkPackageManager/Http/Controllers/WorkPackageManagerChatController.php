<?php

namespace Modules\WorkPackageManager\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Modules\SmsHandler\Http\Controllers\SmsHandlerController;
use Modules\Users\Entities\Users;
use Modules\WorkPackageManager\Entities\WorkPackage;
use Modules\WorkPackageManager\Entities\WorkPackageManagerChat;
use Modules\WorkPackageManager\Http\Requests\WorkPackageChatRequest;

class WorkPackageManagerChatController extends Controller
{
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $ID = $id;
        $chatList = WorkPackageManagerChat::where('work_package_id', $id)->get();
        $WorkPackage = WorkPackage::select(['id', 'status'])->find($id);


        if ($chatList) {
            foreach ($chatList as $key => $item) {
                if ($item->status === 'new' && $item->user_id !== auth()->user()->id) {
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
            'WorkPackage',
            'chatList'
        ];

        return view('workpackagemanager::manager-chat', compact($Data));
    }

    public function store(WorkPackageChatRequest $request, $id)
    {
        $Data = $request->all();
        $WorkPackage = WorkPackage::find($id);
        $Users = Users::where('role', 'admin')->first();

        $Data['work_package_id'] = $id;
        $Data['user_id'] = auth()->user()->id;

        /* Send SMS to Section Manager */
        foreach ($WorkPackage->Section->SectionManager as $manager) {
            if ($manager->id != auth()->user()->id && !empty($manager->phone)) {
                SmsHandlerController::Send(
                    [$manager->phone],
                    "یک پیام مدیریتی در بخش فرایند تایید بسته کاری «{$WorkPackage->title}» ارسال شد."
                );
            }
        }

        /* Send SMS to Owner WorkPackage */
        if (auth()->user()->id != $WorkPackage->uid) {
            SmsHandlerController::Send([$WorkPackage->Users->phone], "یک پیام مدیریتی در بخش فرایند تایید بسته کاری «{$WorkPackage->title}» ارسال شد.");
        }

        /* Send SMS to Super Admin */
        if (auth()->user()->id != $Users->id) {
            SmsHandlerController::Send([$Users->phone], "یک پیام مدیریتی در بخش فرایند تایید بسته کاری «{$WorkPackage->title}» ارسال شد.");
        }

        if (WorkPackageManagerChat::create($Data)) {
            return redirect()->back()->with('notification', [
                'class' => 'success',
                'message' => 'پاسخ ارسال شد.'
            ]);
        }
    }
}
