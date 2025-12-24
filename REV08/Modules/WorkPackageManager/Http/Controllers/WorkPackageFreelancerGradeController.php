<?php

namespace Modules\WorkPackageManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Freelancer\Entities\FreelancerSection;
use Modules\WorkPackageManager\Entities\WorkPackage;
use Modules\WorkPackageManager\Entities\WorkPackageFreelancerGrade;
use Modules\WorkPackageManager\Entities\WorkPackageFreelancerGradeLog;

class WorkPackageFreelancerGradeController extends Controller
{
    public function WorkPackageFreelancerGradeCreate()
    {

    }

    public function WorkPackageFreelancerGradeStore(Request $request)
    {

    }

    public function WorkPackageFreelancerGradeEdit($id)
    {
        $WorkPackageFreelancerGrade = WorkPackageFreelancerGrade::where('work_package_id', $id)->first();
        $FreelancerGradeLog = WorkPackageFreelancerGradeLog::where('work_package_id', $id)->get();
        $Labels = [
            1 => 'کیفیت کار',
            2 => 'مدیریت وظائف و مسئولیت‌ها',
            3 => 'ارتباطات و ارائه بازخورد',
            4 => 'دانش کاربردی و موضوعات مرتبط',
            5 => 'چابکی و پویایی یادگیری',
            6 => 'توانایی تحلیل و حل مسئله',
            7 => 'همسویی با ارزش‌ها',
            8 => 'همکاری',
            10 => 'خلاقیت و نوآوری',
        ];

        $OldSuggestGrades = json_decode($WorkPackageFreelancerGrade->suggest_grade_data ?? '{}', true);
        $OldGrades = json_decode($WorkPackageFreelancerGrade->grade_data ?? '{}', true);
        $GradeLog = WorkPackageFreelancerGradeLog::where('work_package_id', $id)->get();
        $userRole = auth()->user()->role;
        $WorkPackageFreelancerGradeMessage = '';

        if (in_array($userRole, ['chiefAppraiser', 'admin'])) {
            $WorkPackageFreelancerGradeMessage = $WorkPackageFreelancerGrade->grade_message ?? '';
        } elseif ($userRole === 'appraiser') {
            $WorkPackageFreelancerGradeMessage = $WorkPackageFreelancerGrade->suggest_grade_message ?? '';
        }

        $Data = [
            'id',
            'Labels',
            'WorkPackageFreelancerGrade',
            'FreelancerGradeLog',
            'WorkPackageFreelancerGradeMessage',
            'OldSuggestGrades',
            'OldGrades',
            'GradeLog',
        ];
        return view('workpackagemanager::WorkPackageFreelancerGrade.edit', compact($Data));
    }


    public function WorkPackageFreelancerGradeUpdate(Request $request, $id)
    {
        // === محاسبات (بدون تغییر) ===
        $grades = $request->grade_type;
        $weights = [1 => 1, 2 => 0.8, 3 => 0.9, 4 => 1, 5 => 0.8, 6 => 0.9, 7 => 1, 8 => 1, 9 => 0.7, 10 => 1];

        $totalWeighted = 0;
        $totalWeights = 0;
        foreach ($grades as $key => $value) {
            $value = floatval($value);
            $weight = $weights[$key] ?? 1;
            $totalWeighted += $value * $weight;
            $totalWeights += $weight;
        }

        $average = $totalWeights > 0 ? round($totalWeighted / $totalWeights, 2) : 0;
        $gradesJson = json_encode($grades, JSON_UNESCAPED_UNICODE);

        if (!Gate::allows('isChiefAppraiser') && !Gate::allows('isAppraiser')) {
            return back()->with('notification', [
                'class' => 'error',
                'message' => 'شما دسترسی لازم برای ثبت سطح ندارید.'
            ]);
        }

        $WorkPackage = WorkPackage::with('winner_offer.freelancer')->findOrFail($id);
        $WorkPackageFreelancerGrade = WorkPackageFreelancerGrade::where('work_package_id', $id)->first();

        if (!$WorkPackage->winner_offer?->freelancer) {
            return back()->with('notification', [
                'class' => 'error',
                'message' => 'فریلنسر برنده یافت نشد.'
            ]);
        }

        $FreelancerSectionID = FreelancerSection::where('users_id', $WorkPackage->winner_offer->freelancer->users_id)
            ->where('section_id', $WorkPackage->section_id)
            ->where('subsection_id', $WorkPackage->subsection_id)
            ->where('division_id', $WorkPackage->division_id)
            ->first();

        $systemData = [
            'work_package_id' => $id,
            'freelancer_section_id' => $FreelancerSectionID->id,
            'freelancer_id' => $WorkPackage->winner_offer->freelancer->id,
        ];

        $fillableData = [];

        if (auth()->user()->role === 'appraiser') {
            $fillableData = [
                'suggest_grade_message' => $request->grade_message,
                'suggest_grade_data' => $gradesJson,
                'suggest_grade' => $average,
            ];
        } elseif (in_array(auth()->user()->role, ['chiefAppraiser', 'admin'])) {
            $fillableData = [
                'grade_message' => $request->grade_message,
                'grade_data' => $gradesJson,
                'grade' => $average,
            ];
        }

        // === from_grade ===
        $fromGrade = null;
        if (Gate::allows('isChiefAppraiser')) {
            $fromGrade = $WorkPackageFreelancerGrade->grade ?? null;
        } elseif (Gate::allows('isAppraiser')) {
            $fromGrade = $WorkPackageFreelancerGrade->suggest_grade ?? null;
        }

        if ($WorkPackageFreelancerGrade) {

            $WorkPackageFreelancerGrade->update($fillableData);
            $WorkPackageFreelancerGrade->forceFill($systemData)->save();

        } else {

            $WorkPackageFreelancerGrade = new WorkPackageFreelancerGrade();
            $WorkPackageFreelancerGrade->fill($fillableData);
            $WorkPackageFreelancerGrade->forceFill($systemData)->save();
        }

        $log = new WorkPackageFreelancerGradeLog();

        $log->forceFill([
            'work_package_freelancer_grade_id' => $WorkPackageFreelancerGrade->id,
            'user_id' => auth()->id(),
            'freelancer_id' => $systemData['freelancer_id'],
            'work_package_id' => $id,
            'grade_data' => $gradesJson,
            'grade_message' => $request->grade_message,
            'from_grade' => $fromGrade,
            'to_grade' => $average,
        ])->save();

        return back()->with('notification', [
            'class' => 'success',
            'message' => 'سطح با موفقیت ثبت شد.'
        ]);
    }

    public function WorkPackageFreelancerGradeDetails($id)
    {
        $Log = WorkPackageFreelancerGradeLog::find($id);
        $OldGrades = json_decode($Log->grade_data ?? '{}', true);
        $Labels = [
            1 => 'کیفیت کار',
            2 => 'مدیریت وظائف و مسئولیت‌ها',
            3 => 'ارتباطات و ارائه بازخورد',
            4 => 'دانش کاربردی و موضوعات مرتبط',
            5 => 'چابکی و پویایی یادگیری',
            6 => 'توانایی تحلیل و حل مسئله',
            7 => 'همسویی با ارزش‌ها',
            8 => 'همکاری',
            10 => 'خلاقیت و نوآوری',
        ];


        $Data = [
            'OldGrades',
            'Labels',
            'Log',
        ];

        return view('workpackagemanager::WorkPackageFreelancerGrade.details', compact($Data));

    }
}
