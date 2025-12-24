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
        $workPackage = WorkPackage::with('Section.SectionManager', 'Users')->findOrFail($id);
        $admin = Users::where('role', 'admin')->first();

        $data = $request->validated();
        $data['work_package_id'] = $id;
        $data['user_id'] = auth()->id();

        $message = "یک پیام مدیریتی در بخش فرایند تایید بسته کاری «{$workPackage->title}» ارسال شد.";

        // Section Managers
        foreach ($workPackage->Section->SectionManager as $manager) {
            if ($manager->id !== auth()->id() && $manager->phone) {
                SmsHandlerController::Send([$manager->phone], $message);
            }
        }

        // WorkPackage Owner
        if (auth()->id() !== $workPackage->uid && $workPackage->Users?->phone) {
            SmsHandlerController::Send([$workPackage->Users->phone], $message);
        }

        // Super Admin
        if ($admin && auth()->id() !== $admin->id && $admin->phone) {
            SmsHandlerController::Send([$admin->phone], $message);
        }

        WorkPackageManagerChat::create($data);

        return back()->with('notification', [
            'class' => 'success',
            'message' => 'پاسخ ارسال شد.'
        ]);
    }
}
