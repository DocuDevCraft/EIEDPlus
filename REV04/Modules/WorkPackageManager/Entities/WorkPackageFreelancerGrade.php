<?php

namespace Modules\WorkPackageManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Freelancer\Entities\Freelancer;

class WorkPackageFreelancerGrade extends Model
{
    protected $table = 'work_package_freelancer_grade';
    protected $fillable = [
        'work_package_id',
        'freelancer_section_id',
        'freelancer_id',
        'suggest_grade_data',
        'suggest_grade_message',
        'grade_data',
        'grade_message',
        'suggest_grade',
        'grade',
    ];

    public $timestamps = true;

    public function WorkPackage()
    {
        return $this->belongsTo(WorkPackage::class);
    }

    public function Freelancer()
    {
        return $this->belongsTo(Freelancer::class);
    }

    protected static function booted()
    {
        static::saved(function ($grade) {
            // هر بار که نمره ثبت یا تغییر کنه
            if ($grade->wasChanged('grade') || !$grade->getOriginal('grade')) {
                \App\Helpers\FreelancerGradeHelper::calculateFinalGrade(
                    $grade->freelancer_section_id
                );
            }
        });
    }
}
