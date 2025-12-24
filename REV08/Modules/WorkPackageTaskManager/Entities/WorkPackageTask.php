<?php

namespace Modules\WorkPackageTaskManager\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPackageTask extends Model
{
    use HasFactory;

    protected $table = 'wp_task';

    protected $fillable = [
        'work_package_id',
        'category_id',
        'title',
        'desc',
        'price_percentage',
        'prerequisite',
    ];

    public $timestamps = false;

    protected static function newFactory()
    {
        return \Modules\WorkPackageTaskManager\Database\factories\WorkPackageTaskFactory::new();
    }
}
