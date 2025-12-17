<?php

namespace Modules\WorkPackageManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\EmploymentAdvertisement\Entities\EmploymentAdvertisement;
use Modules\Freelancer\Entities\Freelancer;
use Modules\FreelancerOffer\Entities\FreelancerOffer;
use Modules\Payment\Entities\Payment;
use Modules\SectionManager\Entities\Section;
use Modules\SectionManager\Entities\Subsection;
use Modules\Users\Entities\Users;
use Modules\WorkPackageTaskManager\Entities\WorkPackageCategory;

class WorkPackage extends Model
{
    protected $table = 'work_package';

    protected $fillable = [
        'unique_id',
        'uid',
        'package_number',
        'title',
        'desc',
        'section_id',
        'subsection_id',
        'division_id',
        'man_hour',
        'minimum_technical_grade',
        'seniority',
        'package_time_type',
        'package_price_type',
        'recommend_time',
        'recommend_price',
        'winning_formula',
        'minimum_offers',
        'coordinator',
        'daily_fine',
        'fine_after_day',
        'fine_after_price',
        'fine_after_negative',
        'attachment_for_all',
        'attachment_for_winner',
        'rules',
        'offer_time',
        'wp_final_time',
        'wp_final_price',
        'wp_activation_time',
        'signature',
        'tag',
        'work_package_scale',
        'work_package_type',
        'status',
        'offer_list_sorting',
        'offer_list_status',
        'offer_list_file',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'offer_time' => 'datetime',
        'wp_final_time' => 'datetime',
        'wp_activation_time' => 'datetime',

        'man_hour' => 'integer',
        'recommend_time' => 'integer',
        'recommend_price' => 'integer',
        'minimum_offers' => 'integer',
        'daily_fine' => 'integer',
        'fine_after_day' => 'integer',
        'fine_after_price' => 'integer',
        'fine_after_negative' => 'integer',

        'attachment_for_all' => 'boolean',
        'attachment_for_winner' => 'boolean',
    ];
    
    public $timestamps = true;

    public function Users()
    {
        return $this->hasOne(Users::class, 'id', 'uid');
    }

    public function Section()
    {
        return $this->hasOne(Section::class, 'id', 'section_id');
    }

    public function Subsection()
    {
        return $this->hasOne(Subsection::class, 'id', 'subsection_id');
    }

    protected static function newFactory()
    {
        return \Modules\WorkPackageManager\Database\factories\WorkPackageFactory::new();
    }

    public function freelancer_offers()
    {
        return $this->hasMany(FreelancerOffer::class);
    }

    public function winner_offer()
    {
        return $this->hasOne(FreelancerOffer::class)
            ->where('status', 'winner');
    }

    public function wpCategory()
    {
        return $this->hasMany(WorkPackageCategory::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function freelancers()
    {
        return $this->belongsToMany(
            Freelancer::class,
            'work_package_freelancer',
            'work_package_id',
            'freelancer_id'
        );
    }
}
