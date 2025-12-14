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
                'user_id' => auth()->user()->id,
                'to' => json_encode($toArray),
                'type' => 'sms',
                'message' => $message,
                'status' => 'failed',
            ]);
        }

        $data = array(
            'from' => '30004814000024',
            'to' => $toArray,
            'text' => $message
        );
        $payload = json_encode($data);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sapak.me/v1/messages",
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array("Content-Type:application/json", "-X-API-KEY: 12734:9d93798099ec44278196d8cbe5d7b6ce")
        ));
        curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_error($curl);
        curl_close($curl);

        if ($httpcode == 200) {
            if (auth()->check()) {
                $NotificationLog->update(['status' => 'sent']);
            }
            return true;
        } else {
            return false;
        }
    }
}
