<?php

namespace Modules\FreelancerOffer\Entities;

use Illuminate\Database\Eloquent\Model;

class FreelancerOfferContract extends Model
{
    protected $table = 'freelancer_offer_contract';

    protected $fillable = [
        'user_id',
        'work_package_id',
        'contract_no_signed',
        'contract_freelancer_signed',
        'status',
    ];

    public $timestamps = true;
}
