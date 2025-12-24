<?php

namespace Modules\SmsHandler\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\SmsHandler\Entities\NotificationLog;

class SmsHandlerController extends Controller
{
    public static function Send($toArray, $message)
    {
        if (auth()->check()) {

            $NotificationLog = NotificationLog::create([
                'to' => json_encode($toArray),
                'message' => $message,
            ]);

            $NotificationLog->forceFill([
                'user_id' => auth()->user()->id,
                'type' => 'sms',
                'status' => 'failed',
            ])->save();
        }

        $data = [
            'from' => '30004814000024',
            'to' => $toArray,
            'text' => $message
        ];

        $payload = json_encode($data);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.sapak.me/v1/messages",
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type:application/json",
                "-X-API-KEY: 12734:9d93798099ec44278196d8cbe5d7b6ce"
            ]
        ]);

        curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpcode == 200) {
            if (auth()->check()) {
                $NotificationLog->forceFill([
                    'status' => 'sent',
                ])->save();
            }
            return true;
        }

        return false;
    }
}
