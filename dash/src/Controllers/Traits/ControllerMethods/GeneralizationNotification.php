<?php
namespace Dash\Controllers\Traits\ControllerMethods;
use Dash\RenderableElements\Element;
use App\Models\Captain;
trait GeneralizationNotification{

    public function sendNotification($reciever) {

        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey='AAAAGIXjgfY:APA91bGtS4VfEcC3r90nHGBG_0bTzszhSYgl2UwE1W3SitQjgbkPHle7fwaerx27RNeKY2szc0WAMzEvDHbRRad8EjnHsY1w8xgCqb2_XJKtpO_SSIeIDuCvz1cv50TSGDjhSlhG536K';

        $devices= $reciever->toArray();


        $data = [
            "registration_ids" =>array_values( $devices),
            "notification" => [
                "body" =>'you have a generalization message',
                "title" =>'Captain Ask',
                "sound" => "notify.mp3",
            ],
            // "data" => ["type"=>$type]
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
        return($result);
    }


}
