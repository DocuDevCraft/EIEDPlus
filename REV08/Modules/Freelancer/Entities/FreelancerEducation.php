<?php

namespace Modules\Freelancer\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreelancerEducation extends Model
{
    use HasFactory;

    protected $table = 'freelancer_education';

    protected $fillable = [
        'field_of_study',
        'orientation',
        'education_level',
        'university',
        'at_time',
        'to_time',
        'country',
        'city',
        'gpa',
        'education_file'
    ];

    public $timestamps = false;

    protected static function newFactory()
    {
        return \Modules\Freelancer\Database\factories\FreelancerEducationFactory::new();
    }
}
