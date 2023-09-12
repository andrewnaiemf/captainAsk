<?php

namespace App\Notifications;

use Illuminate\Support\Facades\Http;


class PushNotification
{
    public static function send($tokens ,$message, $notification_data = null)
    {
        $screen = '';
        switch ($message) {
            case 'accepted_offer':
                $message = trans('api.Your_offer_is_accepted');
                $screen  = 'home_screen';
                break;

            case 'new_trip':
                $message = trans('api.There_is_a_new_trip');
                $screen  = 'new_request';
                break;

            case 'decline_offer':
                $message = trans('api.There_customer_declined_your_offer');
                $screen  = 'new_request';
                break;

            case 'canceled_trip':
                $message = trans('api.The_trip_is_canceled');
                $screen  = 'home_screen';
                break;

            case 'arrival_message':
                $message = trans('api.the_captain_will_arrive_after_5_mins');
                $screen  = 'track_screen';
                break;

            case 'trip_finished':
                $message = trans('api.the_trip_is_completed_successfully');
                $screen  = 'rate_screen';
                break;

            case 'trip_started':
                $message = trans('api.the_trip_is_started_successfully');
                $screen  = 'track_screen';
                break;

            case 'new_offer':
                $message = trans('api.You_have_new_offer');
                $screen  = 'new_offer';
                break;

            case 'generalization':
                $message = trans('api.You_have_generalization_message');
                $screen  = 'generalization';
                break;

            case 'rating':
                $message = trans('api.Thank_you_for_your_rating');
                $screen  = 'home_screen';
                break;

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
                "tag" => "notification"
            ],
            "data" => [
                'screen' => $screen,
                'notification_data' => json_encode($notification_data),
                "body" => $message,
                "title" => 'Captain ask',
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


    public static function sendNew($reciever, $screen, $message, $notification_data = null)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = env('FCM_KEY') ?? 'AAAA7Npqa5I:APA91bHoZERhWmkNxFYYFhtHhLv0ztX59kYLzU5j3TrIIkRjZrdeSNgDrQcv04UTitAPrB16ODVV5zHnHLHC5FVoBRdS0G1owTTWdTr_G3LL_t5LeLGWgLUXtN-0-x5ZKBMC-bCmSET-';

        $devs=[];
        $devices = $reciever->device_token;
        foreach ($devices as $tokens) {
            if( is_array($tokens) ){
                foreach ($tokens as $token){
                    array_push($devs, $token);
                }
            }else{
                array_push($devs, $tokens);
            }
        }

        $data = [
            "registration_ids" =>$devs,
            "notification" => [
                "body" => $message,
                "title" => 'Captain ask',
                "sound" => "notify.mp3",
                "tag" => "notification"
            ],
            "data" => [
                'screen' => $screen,
                'notification_data' => json_encode($notification_data),
                "body" => $message,
                "title" => 'Captain ask',
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
