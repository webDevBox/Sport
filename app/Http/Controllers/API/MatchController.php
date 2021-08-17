<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Match;
use App\Models\Challange;
use App\Models\MatchesLike;
use App\Models\MatchComment;
use App\Models\AppNotification;
use App\Models\AppNotificationType;
use App\ApiResponse;
use Validator;
use App\PushNotification;

class MatchController extends Controller
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
        //
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

    public function like(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'match_id' => 'required|exists:matches,id'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $user = auth()->user();

        $like = MatchesLike::where('user_id', $user->id)->where('match_id' , $request->match_id)->first();

        if(isset($like))
        {
            $like->delete();
        }
        else
        {
            $like = MatchesLike::create([
                'user_id'       => $user->id,
                'match_id'      => $request->match_id
            ]);

            $type = AppNotificationType::where('class','Match Like')->first();

            $match = Match::find($request->match_id);

            $notification = AppNotification::create([
                'type'          => $type->id,
                'sender_id'     => $user->id,
                'receiver_id'   => $match->challenge->challenger->captain_id,
                'match_id'      => $match->id
            ]);

            $notify = AppNotification::find($notification->id);

            if($match->challenge->challenger->notification == 1)
                PushNotification::firebase($match->challenge->challenger->user->device_token, $user->first_name.' '.$user->last_name, $type->name, $notify);
        }
        $data['like'] = $like;

        return response()->json(ApiResponse::success($data, 'RECORD_UPDATE'));

    }


    public function comment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'match_id'  => 'required|exists:matches,id',
            'comment'   => 'required|string'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $user = auth()->user();

            $comment = MatchComment::create([
                'user_id'   => $user->id,
                'match_id'  => $request->match_id,
                'comment'   => $request->comment
            ]);

            $type = AppNotificationType::where('class','Match Comment')->first();

            $match = Match::find($request->match_id);

            $notification = AppNotification::create([
                'type'          => $type->id,
                'sender_id'     => $user->id,
                'receiver_id'   => $match->challenge->challenger->captain_id,
                'match_id'      => $match->id
            ]);

            $notify = AppNotification::find($notification->id);

            if($match->challenge->challenger->notification == 1)
                PushNotification::firebase($match->challenge->challenger->user->device_token, $user->first_name.' '.$user->last_name.' '.$type->name, $request->comment, $notify);

        $data['comment'] = MatchComment::find($comment->id);

        return response()->json(ApiResponse::success($data, 'MSG_DATA_SAVE'));
    }

    public function getComment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'match_id' => 'required|exists:matches,id',
            'id'       => 'nullable|exists:match_comments,id'
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
            $last = MatchComment::orderBy('id','desc')->first();
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

        $comment = MatchComment::where('match_id',$request->match_id)->where('id', '<=' ,$counter)->take($this->per_page_record)->latest()->get();
        $total = MatchComment::where('match_id',$request->match_id)->count();

        $data['total'] = $total;
        $data['comment'] = $comment;

        return response()->json(ApiResponse::success($data, 'MSG_FOUND'));
    }

    //Matches List API
    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|exists:matches,id'
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
            $last = Match::orderBy('id','desc')->first();
            if(isset($last))
            {
                $counter = $last->id;
            }
            else
            {
                $data['total'] = 0;
                $data['match'] = [];
                return response()->json(ApiResponse::success($data, 'MSG_NOT_FOUND'));
            }
        }

        $match = Match::where('id', '<=' ,$counter)->take($this->per_page_record)->latest()->get();
        $total = Match::count();

        $data['total'] = $total;
        $data['match'] = $match;

        return response()->json(ApiResponse::success($data, 'MATCH_LIST_MSG'));
    }

    public function myMatch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|exists:challanges,id'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $user = auth()->user();
        $data = array();

        $counter = $request->id;
        $counter--;
        if(!isset($request->id))
        {
            $last = Match::where('challanger_id',$user->team_id)->orWhere('opponent_id',$user->team_id)->orderBy('id','desc')->first();

            if(isset($last))
            {
                $counter = $last->id;
            }
            else
            {
                $data['match'] = [];
                return response()->json(ApiResponse::success($data, 'MSG_NOT_FOUND'));
            }
        }

        $search = $user->team_id;

        $match = Match::where('id', '<=' ,$counter)->where(function($query) use ($search){
            $query->where('challanger_id',$search)->orWhere('opponent_id',$search);
        })->latest()->take($this->per_page_record)->get();
        

        $data['match'] = $match;

        return response()->json(ApiResponse::success($data, 'MY_MATCH_LIST_MSG'));
    }


}
