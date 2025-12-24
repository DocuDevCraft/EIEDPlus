<?php

namespace Modules\Notification\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';

    protected $fillable = [
        'title',
        'text',
    ];

    public $timestamps = true;

    protected static function newFactory()
    {
        return \Modules\Notification\Database\factories\NotificationFactory::new();
    }
}
