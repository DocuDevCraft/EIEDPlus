<?php

namespace Modules\UserAccessHandler\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\Users;

class UserAccessHandler extends Model
{
    protected $table = 'user_access_handler';
    protected $fillable = [
        'user_id',
        'access_type',
        'target_id',
        'access_code',
        'receive_user_id',
        'use_time_at',
        'expire_at',
    ];

    public $timestamps = true;

    protected $casts = [
        'use_time_at' => 'datetime',
        'expire_at' => 'datetime',
    ];


    public function Users()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }

    public function receiveUser()
    {
        return $this->hasOne(Users::class, 'id', 'receive_user_id');
    }
}
