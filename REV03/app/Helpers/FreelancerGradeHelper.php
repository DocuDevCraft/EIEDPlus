<?php

namespace App\Helpers;

use Modules\Freelancer\Entities\FreelancerSection;
use Modules\WorkPackageManager\Entities\WorkPackageFreelancerGrade;

class FreelancerGradeHelper
{
    public static function calculateFinalGrade(int $id): ?float
    {
        $freelancerSection = FreelancerSection::find($id);

        if (!$freelancerSection) return null;

        $initialGrade = $freelancerSection->grade ?? null;

        $technicalGrades = WorkPackageFreelancerGrade::where('freelancer_section_id', $id)
            ->whereNotNull('grade')
            ->pluck('grade');

        $technicalAverage = $technicalGrades->count() > 0
            ? round($technicalGrades->avg(), 2)
            : null;


        $finalGrade = null;
        if ($technicalAverage === null && $initialGrade !== null) {
            $finalGrade = round($initialGrade, 2);
        } elseif ($technicalAverage !== null && $initialGrade === null) {
            $finalGrade = round($technicalAverage, 2);
        } elseif ($technicalAverage !== null && $initialGrade !== null) {
            $finalGrade = round(($initialGrade * 0.4) + ($technicalAverage * 0.6), 2);
        }

        if ($finalGrade !== null) {
            $freelancerSection->update(['final_grade' => $finalGrade]);
        }


        return $finalGrade;
    }
}
