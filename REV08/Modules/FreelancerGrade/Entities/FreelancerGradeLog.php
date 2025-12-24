<?php

namespace Modules\FreelancerGrade\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\Users;

class FreelancerGradeLog extends Model
{
    protected $table = 'freelancer_grade_log';
    protected $fillable = [
        'grade_data',
        'grade_message',
        'from_grade',
        'to_grade',
    ];
    public $timestamps = true;

    public function User()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }

    public function Freelancer()
    {
        return $this->hasOne(Users::class, 'id', 'freelancer_id');
    }
}
