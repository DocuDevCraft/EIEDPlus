<?php

namespace Modules\FreelancerGrade\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerGradeChat extends Model
{
    use HasFactory;

    protected $table = 'freelancer_grade_chat';

    protected $fillable = [
        'freelancer_section_id',
        'user_id',
        'message',
    ];

    public $timestamps = true;

    protected static function newFactory()
    {
        return \Modules\FreelancerGrade\Database\factories\FreelancerGradeChatFactory::new();
    }
}
