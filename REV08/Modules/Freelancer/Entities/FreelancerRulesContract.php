<?php

namespace Modules\Freelancer\Entities;

use Illuminate\Database\Eloquent\Model;

class FreelancerRulesContract extends Model
{
    protected $table = 'freelancer_rules_contract';

    protected $fillable = [
        'contract_no_signed',
        'contract_freelancer_signed',
    ];

    public $timestamps = true;
}
