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
use Illuminate\Http\Request;
use Validator;
use App\PushNotification;

class ChallengeController extends Controller
{
    public $per_page_record = 0;
    public function __construct()
    {
        $this->per_page_record = config('constants.PAGINATION_CONSTANTS')['KEY_RECORD_PER_PAGE'];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $team = $user->team;

        $data = array();
        if ($team) {
            $data['challenges']['myChallenges']  = $team->challenges()->Mychallenge()->limit(1)->get(); 
            $data['challenges']['pending']  = $team->challenges()->Pending()->limit(1)->get();
            $data['challenges']['accepted'] = Match::orderBy('id','desc')
            ->where('challanger_id',$user->team_id)->orWhere('opponent_id',$user->team_id)->limit(1)->get();
            return response()->json(ApiResponse::success($data,'CHALLENGE_LIST'));
        }
        return response()->json(ApiResponse::notFound('MSG_NOT_FOUND','TEAM_NOT_EXIST'));
    }

    //My Challenges
    public function myChallenge(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|exists:challanges,id'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $data = array();

        $counter = $request->id;
        $counter--;
        if(!isset($request->id))
        {
            $last = Challange::orderBy('id','desc')->first();
            if(isset($last))
            {
                $counter = $last->id;
            }
            else
            {
                $data['total'] = 0;
                $data['challenge'] = [];
                return response()->json(ApiResponse::success($data, 'MSG_NOT_FOUND'));
            }
        }

        $user = auth()->user();
        $challenge = Challange::orderBy('id','desc')->where('id', '<=' ,$counter)->where('challanger_id',$user->team_id)
        ->whereIn('status',[0,2])->limit(10)->get();

        $total = Challange::where('challanger_id',$user->team_id)->whereIn('status',[0,2])->count();

        $data['total'] = $total;
        $data['challenge'] = $challenge;

        return response()->json(ApiResponse::success($data, 'CHALLENGE_LIST_MSG'));
    }
    
    //My Requested Challenges
    public function requestChallenge(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|exists:challanges,id'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $data = array();

        $counter = $request->id;
        $counter--;
        if(!isset($request->id))
        {
            $last = Challange::orderBy('id','desc')->first();
            if(isset($last))
            {
                $counter = $last->id;
            }
            else
            {
                $data['total'] = 0;
                $data['challenge'] = [];
                return response()->json(ApiResponse::success($data, 'MSG_NOT_FOUND'));
            }
        }

        $user = auth()->user();
        $challenge = Challange::orderBy('id','desc')->where('id', '<=' ,$counter)->where('opponent_id',$user->team_id)
        ->where('status',0)->limit(10)->get();

        $total = Challange::where('opponent_id',$user->team_id)->where('status',0)->count();

        $data['total'] = $total;
        $data['challenge'] = $challenge;

        return response()->json(ApiResponse::success($data, 'CHALLENGE_LIST_MSG'));
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
            'opponent_id'   => 'required|numeric|exists:teams,id',
            'message'       => 'required|string',
            'proposed_time' => 'required|string',
            'venue_id'      => 'required|numeric|exists:venues,id',
            'game_id'       => 'required|numeric|exists:games,id',
            'day_id'        => 'required|numeric|exists:days,id',
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $user = auth()->user();
        $challenger = $user->team;
        if ($challenger){
            $challenge = Challange::create([
                'challanger_id' => $challenger->id,
                'opponent_id'   => $request->opponent_id,
                'message'       => $request->message,
                'proposed_time' => $request->proposed_time,
                'day_id'        => $request->day_id,
                'venue_id'      => $request->venue_id,
                'game_id'       => $request->game_id,
                'actor_id'      => $user->id
            ]);

            /*Send Push notification to opponent*/
            //PushNotificationService::sendNotification($challenge, 'challenge');

            $type = AppNotificationType::where('class','Challenge Invitation')->first();

            $finalChallenge = Challange::find($challenge->id);

            $notification = AppNotification::create([
                'type'          => $type->id,
                'sender_id'     => $user->id,
                'receiver_id'   => $finalChallenge->opponent->captain_id,
                'challange_id'  => $finalChallenge->id
            ]);

            $notify = AppNotification::find($notification->id);
            
            if($finalChallenge->opponent->notification == 1)
                PushNotification::firebase($finalChallenge->opponent->user->device_token, $user->first_name.' '.$user->last_name.' '.$type->name, null, $notify);

            $data['challenge'] = $finalChallenge;
            return response()->json(ApiResponse::success($data,'CHALLENGE_ADDED'));
        }
        return response()->json(ApiResponse::notFound('MSG_NOT_FOUND','MSG_DATA_SAVE'));
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required|numeric|exists:challanges,id',
            'status' => 'required|string'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $user = auth()->user();

        $challenge = Challange::find($request->id);
        $challenge->status = Challange::getStatus($request->status);
        $challenge->save();

        if($request->status == 'accepted')
        {
            $match = Match::create([
                'challange_id' => $request->id,
                'challanger_id' => $challenge->challanger_id,
                'opponent_id' => $challenge->opponent_id,
                'status'       => 0
            ]);

            $type = AppNotificationType::where('class','Challenge Accepted')->first();
            
            $notification = AppNotification::create([
                'type'          => $type->id,
                'sender_id'     => $challenge->opponent->captain_id,
                'receiver_id'   => $challenge->challenger->captain_id,
                'match_id'      => $match->id
            ]);

            $notify = AppNotification::find($notification->id);

            if($challenge->challenger->notification == 1)
                PushNotification::firebase($challenge->challenger->user->device_token, $user->first_name.' '.$user->last_name.' Accept Your Challenge', null, $notify);

            $data['match'] = Match::find($match->id);
            return response()->json(ApiResponse::success($data,'MATCH_ADDED'));
        }

        $type = AppNotificationType::where('class','Challenge Rejected')->first();

            $notification = AppNotification::create([
                'type'          => $type->id,
                'sender_id'     => $challenge->opponent->captain_id,
                'receiver_id'   => $challenge->challenger->captain_id,
                'challange_id'  => $challenge->id
            ]);

            $notify = AppNotification::find($notification->id);

            if($challenge->challenger->notification == 1)
                PushNotification::firebase($challenge->challenger->user->device_token, $user->first_name.' '.$user->last_name.' Reject Your Challenge', null, $notify);

        return response()->json(ApiResponse::update());
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

    public function like(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'challenge_id' => 'required|exists:challanges,id'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $user = auth()->user();

        $like = ChallangeLike::where('user_id', $user->id)->where('challange_id' , $request->challenge_id)->first();

        if(isset($like))
        {
            $like->delete();
        }
        else
        {
            $like = ChallangeLike::create([
                'user_id'       => $user->id,
                'challange_id'  => $request->challenge_id
            ]);
            
            $type = AppNotificationType::where('class','Challenge Like')->first();

            $challenge = Challange::find($request->challenge_id);

            $notification = AppNotification::create([
                'type'          => $type->id,
                'sender_id'     => $user->id,
                'receiver_id'   => $challenge->challenger->captain_id,
                'challange_id'  => $challenge->id
            ]);

            $notify = AppNotification::find($notification->id);

            if($challenge->challenger->notification == 1)
                PushNotification::firebase($challenge->challenger->user->device_token, $user->first_name.' '.$user->last_name, $type->name, $notify);

        }
        $data['like'] = $like;

        return response()->json(ApiResponse::success($data, 'RECORD_UPDATE'));
    }
    
    
    public function comment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'challenge_id' => 'required|exists:challanges,id',
            'comment'      => 'required|string'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $user = auth()->user();

            $comment = ChallangeComment::create([
                'user_id'       => $user->id,
                'challange_id'  => $request->challenge_id,
                'comment'       => $request->comment
            ]);

            $type = AppNotificationType::where('class','Challenge Comment')->first();

            $challenge = Challange::find($request->challenge_id);

            $notification = AppNotification::create([
                'type'          => $type->id,
                'sender_id'     => $user->id,
                'receiver_id'   => $challenge->challenger->captain_id,
                'challange_id'  => $challenge->id
            ]);

            $notify = AppNotification::find($notification->id);

            if($challenge->challenger->notification == 1)
                PushNotification::firebase($challenge->challenger->user->device_token, $user->first_name.' '.$user->last_name.' '.$type->name, $request->comment, $notify);
        
        $data['comment'] = ChallangeComment::find($comment->id);

        return response()->json(ApiResponse::success($data, 'MSG_DATA_SAVE'));
    }

    public function getComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'challenge_id' => 'required|exists:challanges,id',
            'id'           => 'nullable|exists:challange_comments,id'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $data = array();

        $counter = $request->id;
        $counter--;
        if(!isset($request->id))
        {
            $last = ChallangeComment::orderBy('id','desc')->first();
            if(isset($last))
            {
                $counter = $last->id;
            }
            else
            {
                $data['total'] = 0;
                $data['comment'] = [];
                return response()->json(ApiResponse::success($data, 'MSG_NOT_FOUND'));
            }
        }

        $comment = ChallangeComment::where('challange_id',$request->challenge_id)->orderBy('id','desc')->where('id', '<=' ,$counter)->limit(10)->get();
        $total = ChallangeComment::where('challange_id',$request->challenge_id)->count();

        $data['total'] = $total;
        $data['comment'] = $comment;

        return response()->json(ApiResponse::success($data, 'MSG_FOUND'));
    }

    //Rejected and Pending challenges List
    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'    => 'nullable|exists:challanges,id',
            'type'  => 'required|in:' . implode(',', ['pending', 'rejected'])
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $data = array();
        
        if($request->type == 'pending')
        {
            $counter = --$request->id;
            if(!isset($request->id))
            {
                $last = Challange::where('status',0)->latest()->first();
                if(isset($last))
                {
                    $counter = $last->id;
                }
                else
                {
                    $data['pending'] = [];
                    return response()->json(ApiResponse::success($data, 'MSG_NOT_FOUND'));
                }
            }

            $pending = Challange::where('status',0)->where('id', '<=' ,$counter)->latest()->take($this->per_page_record)->get();

            $data['pending'] = $pending;

            return response()->json(ApiResponse::success($data, 'PENDING_CHALLENGE_LIST'));
        }
        else
        {
            $counter = --$request->id;
            if(!isset($request->id))
            {
                $last = Challange::where('status',2)->latest()->first();
                if(isset($last))
                {
                    $counter = $last->id;
                }
                else
                {
                    $data['rejected'] = [];
                    return response()->json(ApiResponse::success($data, 'MSG_NOT_FOUND'));
                }
            }

            $reject = Challange::where('status',2)->where('id', '<=' ,$counter)->latest()->take($this->per_page_record)->get();

            $data['rejected'] = $reject;

            return response()->json(ApiResponse::success($data, 'REJECTED_CHALLENGE_LIST'));
        }
    }

}
