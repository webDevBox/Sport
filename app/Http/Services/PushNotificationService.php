<?php


namespace App\Http\Services;


use App\Models\Team;

class PushNotificationService
{
    /**
     * Send Push notification using firebase
     *
     * @param  Array $notificationData
     * @param  String $type
     * @return \Illuminate\Http\Response
     */
    public static function sendNotification($notificationData, $type)
    {
//        dd($notificationData);
        $chalengerTeam = Team::where('id', $notificationData->challanger_id)->first();
        $opponentTeam  = Team::where('id', $notificationData->opponent_id)->first();

        $opponentUser   = $opponentTeam->user;
        $device_token   = $opponentUser->device_token;

        $fcmUrl = config('services.firebase')['url'];
        $token = $device_token;


//        if($notificationData->type == 'like')
//        {
//            $type  = "Like";
//            $title = $chalengerTeam->name.' started following you.';
//            $body  = $notificationData['sender']->name.' started following you.';
//        }
//        if($notificationData->type == 'comment')
//        {
//            $type  = "Comment";
//            $title = $notificationData['sender']->name.' Commented on your video.';
//            $body  = $notificationData['sender']->name.' Commented on your video.';
//        }
        if($type == 'challenge')
        {
//            dd($chalengerTeam);
            $type  = "Challenge";
            $title = $chalengerTeam->name.' Challenge you';
            $body  = $chalengerTeam->name.' Challenge you';
        }

//        if ($notificationData->type_id == 5) {
//            $type  = "Chat Message";
//            $title = "New Chat Message from ".$notificationData->sender->name;
//            $body  = $notificationData->MessageUnreadCount.' new messages from '.$notificationData->sender->name;
//        }

        $extraNotificationData = $notificationData;
//dd($type.' '.$title.' '.$body);
        $notification = [
            'title' => $title,
            'body'  => $body,
            'sound' => true,
            'data'  => $extraNotificationData
        ];

        // $extraNotificationData = $notificationData;

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'           => $token, //single token
            'notification' => $notification,
            'data'         => $extraNotificationData
        ];

        $headers = [
            'Authorization: key='.config('services.firebase')['serverkey'],
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result, true);
        if ($response['failure'] == 1) {
            \Log::info($type.' notification is not being sent against device token "'.$token.'" and status is , '. $response['results'][0]['error']);
        }
    }
}
