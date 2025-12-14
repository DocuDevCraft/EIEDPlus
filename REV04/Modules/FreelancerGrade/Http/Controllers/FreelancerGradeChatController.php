<?php

namespace Modules\FreelancerGrade\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Freelancer\Entities\FreelancerSection;
use Modules\FreelancerGrade\Entities\FreelancerGradeChat;
use Modules\SectionManager\Entities\Section;
use Modules\SmsHandler\Http\Controllers\SmsHandlerController;
use Modules\WorkPackageManager\Http\Requests\WorkPackageChatRequest;

class FreelancerGradeChatController extends Controller
{
    public function store(WorkPackageChatRequest $request)
    {
        $Data = $request->all();
        $Data['freelancer_section_id'] = $request->id;
        $Data['user_id'] = auth()->user()->id;

        if (FreelancerGradeChat::create($Data)) {
            $ChiefAppraiser = Section::find(FreelancerSection::find($request->id)->section_id)->ChiefAppraiser;

            if ($ChiefAppraiser) {
                foreach ($ChiefAppraiser as $item) {
                    SmsHandlerController::Send(["$item->phone"], "سلام، یک فریلنسر در انتظار ارزیابی شما می باشد.");
                }
            }

            SmsHandlerController::Send(["$item->phone"], "نمره فنی ارزیابی شده توسط ارزیاب در انتظار تایید شما می باشد.");
            return redirect()->back()->with('notification', [
                'class' => 'success',
                'message' => 'پاسخ ارسال شد.'
            ]);
        }
    }
}
