<?php

namespace Modules\WorkPackageTaskManager\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPackageProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'attachment',
    ];

    protected static function newFactory()
    {
        return \Modules\WorkPackageTaskManager\Database\factories\WorkPackageProgressFactory::new();
    }
}
