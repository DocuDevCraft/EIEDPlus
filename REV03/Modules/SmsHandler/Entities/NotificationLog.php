<?php

namespace Modules\SmsHandler\Entities;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{

    protected $table = 'notification_log';

    protected $fillable = [
        'user_id',
        'type',
        'to',
        'message',
        'status'
    ];

    public $timestamps = true;
}
