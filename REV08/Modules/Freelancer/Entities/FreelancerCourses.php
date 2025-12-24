<?php

namespace Modules\Freelancer\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerCourses extends Model
{
    use HasFactory;

    protected $table = 'freelancer_courses';

    protected $fillable = [
        'title',
        'academy',
        'at_time',
        'to_time',
        'course_file',
    ];

    public $timestamps = false;

    protected static function newFactory()
    {
        return \Modules\Freelancer\Database\factories\FreelancerCoursesFactory::new();
    }
}
