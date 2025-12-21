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
        $data = $request->validated();
        $data['freelancer_section_id'] = $request->id;
        $data['user_id'] = auth()->id();

        FreelancerGradeChat::create($data);

        $sectionId = FreelancerSection::where('id', $request->id)->value('section_id');
        $chiefAppraisers = Section::find($sectionId)?->ChiefAppraiser;

        if ($chiefAppraisers) {
            foreach ($chiefAppraisers as $item) {
                SmsHandlerController::Send(
                    [$item->phone],
                    'سلام، یک فریلنسر در انتظار ارزیابی شما می‌باشد.'
                );
            }
        }

        return redirect()->back()->with('notification', [
            'class' => 'success',
            'message' => 'پاسخ ارسال شد.'
        ]);
    }
}
