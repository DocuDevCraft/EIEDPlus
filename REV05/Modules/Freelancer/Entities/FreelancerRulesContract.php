<?php

namespace Modules\Freelancer\Entities;

use Illuminate\Database\Eloquent\Model;

class FreelancerRulesContract extends Model
{
    protected $table = 'freelancer_rules_contract';

    protected $fillable = [
        'user_id',
        'contract_no_signed',
        'contract_freelancer_signed',
        'status',
    ];

    public $timestamps = true;
}
