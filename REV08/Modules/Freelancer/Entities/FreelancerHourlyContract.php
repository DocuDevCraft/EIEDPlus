<?php

namespace Modules\Freelancer\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\Users;

class FreelancerHourlyContract extends Model
{
    protected $table = 'freelancer_hourly_contract';

    protected $fillable = [
        'contract_no_signed',
        'contract_freelancer_signed',
    ];

    public $timestamps = true;

    public function users()
    {
        return $this->belongsTo(Users::class, 'user_id', 'id');
    }

    public function freelancer()
    {
        return $this->belongsTo(Freelancer::class, 'user_id', 'users_id');
    }
}
