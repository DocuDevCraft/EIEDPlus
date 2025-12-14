<?php

namespace Modules\Freelancer\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerWorkExperience extends Model
{
    use HasFactory;

    protected $table = 'freelancer_work_experience';

    protected $fillable = [
        'users_id',
        'activity_type',
        'post',
        'field',
        'company',
        'at_time',
        'to_time',
        'country',
        'address',
        'phone',
        'website',
        'work_experience_file',
    ];

    public $timestamps = false;

    protected static function newFactory()
    {
        return \Modules\Freelancer\Database\factories\FreelancerWorkExperienceFactory::new();
    }
}
