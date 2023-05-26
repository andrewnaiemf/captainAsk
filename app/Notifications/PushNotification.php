<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Http;


class PushNotification
{
    public static function send($tokens ,$message)
    {
        $screen = '';
        switch ($message) {
            case 'accepted_offer':
                $message = 'Your offer is accepted';
                $screen  = 'home_screen';
                break;

            case 'new_trip':
                $message = 'There is a new trip';
                $screen  = 'new_request';
                break;

            case 'arrival_message':
                $message = 'the captain will arrive after 5 mins';
                $screen  = 'track_screen';
                break;

            case 'new_offer':
                $message = 'You have new offer';
                $screen  = 'new_offer';

            default:
                $screen  = 'home_screen';//for captain app
                break;
        }

        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = env('FCM_KEY') ?? 'AAAA7Npqa5I:APA91bHoZERhWmkNxFYYFhtHhLv0ztX59kYLzU5j3TrIIkRjZrdeSNgDrQcv04UTitAPrB16ODVV5zHnHLHC5FVoBRdS0G1owTTWdTr_G3LL_t5LeLGWgLUXtN-0-x5ZKBMC-bCmSET-';
        $devs=[];
        $devices = $tokens;
        foreach ($devices as $tokens) {
            if( $tokens){
                foreach ($tokens as $token){
                    array_push($devs, $token);
                }
            }
        }

        $data = [
            "registration_ids" =>$devs,
            "notification" => [
                "body" => $message,
                "title" => 'Captain ask',
                "sound" => "notify.mp3",
            ],
            "data" => [
                'screen' => $screen
            ]
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        // FCM response
        return json_decode($result);
    }
}
