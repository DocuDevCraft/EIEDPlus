<?php

namespace Modules\Freelancer\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\SectionManager\Entities\Division;
use Modules\SectionManager\Entities\Section;
use Modules\SectionManager\Entities\Subsection;
use Modules\Users\Entities\Users;

class FreelancerSection extends Model
{
    use HasFactory;

    protected $table = 'freelancer_section';

    protected $fillable = [
        'section_id',
        'subsection_id',
        'division_id',
        'suggest_grade_data',
        'suggest_grade_message',
        'grade_data',
        'grade_message',
        'suggest_grade',
        'grade',
        'final_grade',
    ];

    public $timestamps = false;

    protected static function newFactory()
    {
        return \Modules\Freelancer\Database\factories\FreelancerSectionFactory::new();
    }


    public function users()
    {
        return $this->belongsTo(Users::class);
    }

    public function freelancer()
    {
        return $this->belongsTo(Freelancer::class, 'users_id', 'users_id');
    }

    public function sectionTable()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function subSectionTable()
    {
        return $this->belongsTo(Subsection::class, 'subsection_id');
    }

    public function divisionTable()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    protected static function booted()
    {
        static::saved(function ($freelancerSection) {
            if ($freelancerSection->wasChanged('grade') || !$freelancerSection->getOriginal('grade')) {
                \App\Helpers\FreelancerGradeHelper::calculateFinalGrade(
                    $freelancerSection->id
                );
            }
        });
    }
}
