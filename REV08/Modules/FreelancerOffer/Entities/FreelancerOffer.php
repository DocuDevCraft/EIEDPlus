<?php

namespace Modules\FreelancerOffer\Entities;

use App\Helpers\WorkPackageScaleHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Freelancer\Entities\Freelancer;
use Modules\Freelancer\Entities\FreelancerSection;
use Modules\Users\Entities\Users;
use Modules\WorkPackageManager\Entities\WorkPackage;

class FreelancerOffer extends Model
{
    use HasFactory;

    protected $table = 'freelancer_offer';
    protected $fillable = [
        'price',
        'time',
        'attachment',
    ];

    public $timestamps = true;

    public function users()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function freelancer()
    {
        return $this->belongsTo(Freelancer::class, 'user_id', 'users_id');
    }

    public function workPackageTable()
    {
        return $this->belongsTo(WorkPackage::class, 'work_package_id');
    }

    public function grade()
    {
        return $this->belongsTo(FreelancerSection::class, 'user_id', 'users_id');
    }

    protected static function newFactory()
    {
        return \Modules\FreelancerOffer\Database\factories\FreelancerOfferFactory::new();
    }

    protected static function booted()
    {
        static::created(function ($offer) {
            WorkPackageScaleHelper::updateScale($offer->work_package_id);
        });

        static::updated(function ($offer) {
            if ($offer->wasChanged('price')) {
                WorkPackageScaleHelper::updateScale($offer->work_package_id);
            }
        });
    }
}
