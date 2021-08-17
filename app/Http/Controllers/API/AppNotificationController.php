<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AppNotification;
use App\Models\Team;
use Illuminate\Http\Request;
use Validator;
use App\ApiResponse;

class AppNotificationController extends Controller
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

        $user = auth()->user();

        $data = array();

        $notification = AppNotification::where('receiver_id',$user->id)->unread()
        ->orderBy('id','desc')->paginate($this->per_page_record);
        
        $data['notifications'] = $notification;

        return response()->json(ApiResponse::success($data, 'MSG_FOUND'));
    }

    //Update is Read Status
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'  => 'required|exists:app_notifications,id'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        AppNotification::where('id',$request->id)->update(['is_read' => 1]);

        return response()->json(ApiResponse::update());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notification()
    {
        $user = auth()->user();
        if($user->team_id != null)
        {
            $team = Team::find($user->team_id);

            if($team->notification == 1)
                $team->notification = 0;
            else
                $team->notification = 1;

            $team->save();

            $data['team'] = $team;

            return response()->json(ApiResponse::success($data, 'TEAM_NOTIFICATION_UPDATED'));
        }
        return response()->json(ApiResponse::error('TEAM_NOT_FOUND', 'RESPONSE_CODE_NOT_FOUND'));
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $user = auth()->user();
        $notifications = AppNotification::where('receiver_id',$user->id)->delete();
        return response()->json(ApiResponse::success(null,'NOTIFICATION_DELETE'));
    }
}
