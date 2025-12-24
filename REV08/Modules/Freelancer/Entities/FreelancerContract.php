<?php

namespace Modules\Freelancer\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\Users;
use Modules\WorkPackageManager\Entities\WorkPackage;

class FreelancerContract extends Model
{
    protected $table = 'freelancer_contract';
    protected $fillable = [
        'contract_no_signed',
        'contract_freelancer_signed',
        'contract_employer_signed',
    ];

    public $timestamps = true;

    public function users()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function workPackage()
    {
        return $this->belongsTo(WorkPackage::class, 'work_package_id', 'id')->select('id', 'package_number', 'title', 'wp_final_price');
    }
}
