<?php

namespace Modules\Notification\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Notification\Entities\Notification;

class NotificationController extends Controller
{
    public function get()
    {
        $Notification = Notification::where('user_id', auth('sanctum')->user()->id)->orderBy('created_at', 'desc')->get();

        return response()->json(['getData' => $Notification]);
    }

    public static function store($title, $text, $user = '')
    {
        if (!$user) {
            $user = auth('sanctum')->user()->id ?: auth()->user()->id;
        }

        $notification = Notification::create([
            'title' => $title,
            'text' => $text,
        ]);

        $notification->forceFill([
            'user_id' => $user,
            'status' => 'new',
            'type' => 'system',
        ])->save();

        return $notification;
    }

    public function viewed($id)
    {
        $notification = Notification::findOrFail($id);

        $notification->forceFill([
            'status' => 'viewed',
        ])->save();
    }
}
