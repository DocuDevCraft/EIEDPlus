<?php

namespace Modules\SmsHandler\Entities;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{

    protected $table = 'notification_log';

    protected $fillable = [
        'to',
        'message',
    ];

    public $timestamps = true;
}
