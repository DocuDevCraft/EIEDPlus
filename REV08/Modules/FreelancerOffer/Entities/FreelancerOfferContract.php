<?php

namespace Modules\FreelancerOffer\Entities;

use Illuminate\Database\Eloquent\Model;

class FreelancerOfferContract extends Model
{
    protected $table = 'freelancer_offer_contract';

    protected $fillable = [
        'contract_no_signed',
        'contract_freelancer_signed',
    ];

    public $timestamps = true;
}
