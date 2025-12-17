<?php

namespace Modules\WorkPackageTaskManager\Entities;

use Illuminate\Database\Eloquent\Model;

class WorkPackageActivity extends Model
{
    protected $table = 'wp_activity';

    protected $fillable = [
        'work_package_id',
        'title',
        'price_percentage',
    ];

    public $timestamps = false;
}
