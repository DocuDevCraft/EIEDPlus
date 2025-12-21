<?php

namespace Modules\WorkPackageManager\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Users\Entities\Users;

class WorkPackageManagerChat extends Model
{
    use HasFactory;

    protected $table = 'work_package_manager_chat';
    protected $fillable = [
        'work_package_id',
        'user_id',
        'message',
        'attachment',
        'status',
    ];

    public function users()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    protected static function newFactory()
    {
        return \Modules\WorkPackageManager\Database\factories\WorkPackageManagerChatFactory::new();
    }
}
