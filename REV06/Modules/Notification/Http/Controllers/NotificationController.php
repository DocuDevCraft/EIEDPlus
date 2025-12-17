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
        return Notification::create([
            'user_id' => $user,
            'title' => $title,
            'text' => $text,
            'status' => 'new',
            'type' => 'system',
        ]);
    }

    public function viewed($id) {
        Notification::find($id)->update(['status' => 'viewed']);
    }
}
