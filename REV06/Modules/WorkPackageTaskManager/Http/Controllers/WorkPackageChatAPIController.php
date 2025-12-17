<?php

namespace Modules\WorkPackageTaskManager\Http\Controllers;

use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\FileLibrary\Http\Controllers\FileLibraryController;
use Modules\FreelancerOffer\Entities\FreelancerOffer;
use Modules\WorkPackageManager\Entities\WorkPackage;
use Modules\WorkPackageManager\Entities\WorkPackageChat;

class WorkPackageChatAPIController extends Controller
{
    public function get($id)
    {
        $freelancerOffer = FreelancerOffer::where('user_id', auth('sanctum')->user()->id)->where('work_package_id', $id)->where('status', 'winner')->count();

        if ($freelancerOffer) {
            $chatList = WorkPackageChat::with('users')->where('work_package_id', $id)->where('type', 'on_board')->where('replay_to_user_id', null)->get();
            $workPackage = WorkPackage::select('id', 'package_number', 'title')->find($id);
            $Data['workPackage'] = $workPackage;

            if ($chatList) {
                $Data['chatList'] = $chatList;

                foreach ($chatList as $key => $item) {
                    $Parent = '';
                    $Parent = WorkPackageChat::with('users')->where('replay_to_user_id', $item->id)->where('status', 'published');

                    $Parent->update(['viewed' => 1]);


                    if ($Parent->get()) {
                        $chatList[$key]['parent'] = $Parent->get();

                    }

                    if ($item->attachment) {
                        $item['attachment'] = [
                            'file_name' => HomeController::GetFileName($item->attachment),
                            'path' => HomeController::GetFilePath($item->attachment)
                        ];
                    }
                }
            }

//            foreach ($chatList as $key => $item) {
//                if ($item->attachment) {
//                    $item['attachment'] = [
//                        'name' => HomeController::GetFileName($item->attachment),
//                        'path' => HomeController::GetFilePath($item->attachment)
//                    ];
//                }
//            }
            return response()->json(['getData' => $Data]);
        } else {
            return response()->json(['getData' => 'no_permission']);
        }
    }

    public function store(Request $request, $id)
    {
        $data = $request->validate([
            'message' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,webp,pdf,doc,docx',
        ]);

        $data += [
            'work_package_id' => $id,
            'user_id' => auth('sanctum')->id(),
            'type' => 'on_board',
            'status' => 'new',
        ];

        if ($request->hasFile('attachment')) {
            $data['attachment'] = FileLibraryController::upload(
                $request->file('attachment'),
                'file',
                'work-package/chat',
                'Work Package chat'
            );
        }

        WorkPackageChat::create($data);

        return response()->json(['status' => 200]);
    }
}
