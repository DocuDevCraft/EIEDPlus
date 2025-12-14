<?php

namespace Modules\WorkPackageTaskManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkPackageProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'attachment',
        'status',
    ];

    protected static function newFactory()
    {
        return \Modules\WorkPackageTaskManager\Database\factories\WorkPackageProgressFactory::new();
    }
}
