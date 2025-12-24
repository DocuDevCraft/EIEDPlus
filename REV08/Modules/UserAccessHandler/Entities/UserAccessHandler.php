<?php

namespace Modules\UserAccessHandler\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\Users;

class UserAccessHandler extends Model
{
    protected $table = 'user_access_handler';
    protected $fillable = [
        'access_type',
        'access_code',
    ];

    public $timestamps = true;

    public function Users()
    {
        return $this->hasOne(Users::class, 'id', 'user_id');
    }

    public function receiveUser()
    {
        return $this->hasOne(Users::class, 'id', 'receive_user_id');
    }
}
