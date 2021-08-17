<?php

namespace App\Http\Controllers\API;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Services\PushNotificationService;
use App\Models\Challange;
use App\Models\Match;
use App\Models\Team;
use App\Models\ChallangeLike;
use App\Models\ChallangeComment;
use App\Models\AppNotification;
use App\Models\AppNotificationType;
use App\Models\Chat;
use Illuminate\Http\Request;
use Validator;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'receiver_team' => 'required|exists:teams,id',
            'match_id'      => 'required|exists:matches,id',
            'chat_id'       => 'required'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $user = auth()->user();

        $chat = Chat::create([
            'sender_team'   => $user->team_id,
            'receiver_team' => $request->receiver_team,
            'match_id'      => $request->match_id,
            'chat_id'       => $request->chat_id
        ]);

        $receiverTeam = Chat::find($chat->id);

        $type = AppNotificationType::where('class','Chat Request')->first();

        $notification = AppNotification::create([
            'type'          => $type->id,
            'sender_id'     => $user->id,
            'receiver_id'   => $receiverTeam->receiver->user->id,
            'match_id'      => $request->match_id
        ]);

        $notify = AppNotification::find($notification->id);

            $SERVER_API_KEY = config('services.firebase')['FIREBASE_SERVER_KEY'];
            $firebase = [
                "registration_ids" => [$receiverTeam->receiver->user->device_token],
                "notification" => [
                    "title" => $user->first_name.' '.$user->last_name.' '.$type->name,
                ],
                "data" => $notify
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
            
            $data['chat'] = $receiverTeam;
            return response()->json(ApiResponse::success($data,'MESSAGE_SEND_SUCCESSFULLY'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
