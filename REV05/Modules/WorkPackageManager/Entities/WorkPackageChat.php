<?php

namespace Modules\WorkPackageManager\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\Users;

class WorkPackageChat extends Model
{
    use HasFactory;

    protected $table = 'work_package_chat';
    protected $fillable = [
        'work_package_id',
        'user_id',
        'replay_to_user_id',
        'message',
        'attachment',
        'type',
        'status',
    ];

    public $timestamps = true;

    public function users()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    protected static function newFactory()
    {
        return \Modules\WorkPackageManager\Database\factories\WorkPackageChatFactory::new();
    }
}
