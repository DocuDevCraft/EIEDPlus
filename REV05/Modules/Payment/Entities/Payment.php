<?php

namespace Modules\Payment\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Freelancer\Entities\Freelancer;
use Modules\Users\Entities\Users;
use Modules\WorkPackageManager\Entities\WorkPackage;
use Modules\WorkPackageTaskManager\Entities\WorkPackageActivity;
use Modules\WorkPackageTaskManager\Entities\WorkPackageCategory;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment';

    protected $fillable = [
        'users_id',
        'work_package_id',
        'activity_id',
        'category_id',
        'amount',
        'status',
    ];

    protected $with = array('Users', 'workPackage', 'activity', 'category', 'Freelancer');

    public function Users()
    {
        return $this->belongsTo(Users::class, 'users_id', 'id')->select('id', 'first_name', 'last_name', 'phone');
    }

    public function Freelancer()
    {
        return $this->belongsTo(Freelancer::class, 'users_id', 'users_id')->select('id', 'users_id', 'shaba', 'meli_code');
    }

    public function workPackage()
    {
        return $this->belongsTo(WorkPackage::class, 'work_package_id', 'id')->select('id', 'package_number', 'title', 'wp_final_price', 'status');

    }

    public function activity()
    {
        return $this->belongsTo(WorkPackageActivity::class, 'activity_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(WorkPackageCategory::class, 'category_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Payment\Database\factories\PaymentFactory::new();
    }
}
