<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Modules\FileLibrary\Entities\FileLibrary;
use Modules\Freelancer\Entities\Freelancer;
use Modules\Freelancer\Entities\FreelancerCourses;
use Modules\Freelancer\Entities\FreelancerEducation;
use Modules\SectionManager\Entities\Section;
use Modules\SectionManager\Entities\Subsection;


class Users extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function isProfileComplete(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->freelancer
                && $this->freelancer->meli_code
                && $this->freelancer->cardserialno
                && $this->freelancer->birthday
                && $this->freelancer->birthday_miladi
                && $this->email
                && $this->phone
                && $this->first_name
                && $this->last_name
        );
    }


    protected static function newFactory()
    {
        return \Modules\Users\Database\factories\UsersFactory::new();
    }

    public function avatar_tbl()
    {
        return $this->hasOne(FileLibrary::class);
    }

    public function freelancer()
    {
        return $this->hasOne(Freelancer::class);
    }

    public function education()
    {
        return $this->hasMany(FreelancerEducation::class);
    }

    public function courses()
    {
        return $this->hasMany(FreelancerCourses::class);
    }

    public function Section()
    {
        return $this->belongsToMany(Section::class);
    }

    public function SubSection()
    {
        return $this->belongsToMany(Subsection::class);
    }
}
