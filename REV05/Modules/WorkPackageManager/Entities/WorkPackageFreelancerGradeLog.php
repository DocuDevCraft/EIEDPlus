<?php

namespace Modules\WorkPackageManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\Users;

class WorkPackageFreelancerGradeLog extends Model
{
    protected $table = 'work_package_freelancer_grade_log';

    protected $fillable = [
        'work_package_freelancer_grade_id',
        'user_id',
        'freelancer_id',
        'work_package_id',
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
}
