<?php

namespace App;

use Config;

class PushNotification{
    public static function firebase($token, $title, $body=null, $data=null)
    {
        $SERVER_API_KEY = config('services.firebase')['FIREBASE_SERVER_KEY'];
            $firebase = [
                "registration_ids" => $token,
                "notification" => [
                    "title" => $title,
                    "body" => $body,  
                ],
                "data" => $data
            ];
            $dataString = json_encode($firebase);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();
      
            curl_setopt($ch, CURLOPT_URL, config('services.firebase')['FIREBASE_URL']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
                
            $response = curl_exec($ch);
    }
}