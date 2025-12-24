<?php

namespace Modules\Freelancer\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\Users;

class Freelancer extends Model
{
    use HasFactory;

    protected $table = 'freelancer';
    protected $fillable = [
        'meli_code',
        'cardserialno',
        'shenasnameh',
        'mahale_sodoor',
        'country',
        'address',
        'sarbazi',
        'sarbazi_file',
        'linkedin',
        'website',
        'birthday',
        'birthday_miladi',
        'home_phone',
        'postal_code',
        'biography',
        'tax',
        'tax_value',
        'tax_file',
        'shaba',
        'resume_file',
        'auth_verify',
        'hourly_rate',
        'certpass'
    ];
    public $timestamps = false;

    protected static function newFactory()
    {
        return \Modules\Freelancer\Database\factories\FreelancerFactory::new();
    }

    public function users()
    {
        return $this->belongsTo(Users::class);
    }

    public function FreelancerSection()
    {
        return $this->hasMany(FreelancerSection::class, 'users_id', 'users_id');
    }


}
