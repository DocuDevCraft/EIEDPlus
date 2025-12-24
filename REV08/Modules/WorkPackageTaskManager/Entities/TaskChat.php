<?php

namespace Modules\WorkPackageTaskManager\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\Entities\Users;

class TaskChat extends Model
{
    use HasFactory;

    protected $table = 'task_chat';

    protected $fillable = [
        'message',
        'attachment',
    ];

    public $timestamps = true;

    public function users_tbl()
    {
        return $this->belongsTo(Users::class, 'users_id')->select('id', 'first_name', 'last_name', 'role');
    }

    protected static function newFactory()
    {
        return \Modules\WorkPackageTaskManager\Database\factories\TaskChatFactory::new();
    }
}
