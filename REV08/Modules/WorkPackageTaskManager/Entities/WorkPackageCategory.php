<?php

namespace Modules\WorkPackageTaskManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Entities\Payment;
use Modules\WorkPackageManager\Entities\WorkPackage;

class WorkPackageCategory extends Model
{
    protected $table = 'wp_category';

    protected $fillable = [
        'work_package_id',
        'activity_id',
        'title',
        'stage',
        'price_percentage',
        'due_date',
    ];

    public $timestamps = false;

    public function Task()
    {
        return $this->hasMany(WorkPackageTask::class, 'category_id', 'id');
    }

    public function workPackage()
    {
        return $this->belongsTo(WorkPackage::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'category_id', 'id');
    }
}
