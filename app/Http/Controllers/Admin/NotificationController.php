<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Challange;
use App\Models\Match;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\ApiResponse;
use App\PushNotification;
use App\Models\User;
use App\Models\Game;
use App\Models\Days;
use App\Models\Team;
use App\Models\Notification;
use App\Models\NotificationTeam;
use App\Models\AppNotification;
use App\Models\AppNotificationType;
use Validator;
use DataTables;

class NotificationController extends Controller
{
    public function index(Request $request){
        
        $notifications = Notification::latest()->get();
        $total = Notification::count();

        if ($request->ajax()) {
            return Datatables::of($notifications)
                
                ->addColumn('title', function($title){

                    return $title->title;
                })
                ->addColumn('body', function($body){

                    return $body->body;
                })
                ->addColumn('created',function ($created) {
                    return $created->created_at;
                })
                ->addColumn('sport',function ($sport) {
                    return $sport->game->name;
                })
                ->addColumn('teams',function ($teams) {
                    if(count($teams->teams) == 0) return 'All Teams'; 
                    else return count($teams->teams);
                })
                ->rawColumns(['title', 'body', 'created', 'sport', 'teams'])
                ->make(true);     
        }
        return view('admin.notifications.notificationList')->with('total',$total);
    }

    public function create(){
        $games = Game::active()->get();
        return view('admin.notifications.creator')->with('games',$games);
    }

    public function getTeams(Request $request)
    {
        $teams = Team::where('game_id',$request->game)->get();

        $data_array = [
            "success" => [
            "status" => "1",
            "teams" => $teams,
            ]
        ];
        return response()->json($data_array);
    }

    public function store(Request $request)
    {
            $this->validate($request,[
                'game'         => 'required|numeric|exists:games,id',
                'team'         => 'Array',
                'title'        => 'required|string|max:200',
                'description'  => 'required|string|max:200',
            ]);
            
            $teamStatus = 0;
            
            $type = AppNotificationType::where('class','Admin Notification')->first();

            $admin = Auth::guard('admin')->user();

            $notification = Notification::create([
                'game_id' => $request->game,
                'title'   => $request->title,
                'body'    => $request->description
            ]);

            $data = array();

            if(isset($request->team))
            {
                for($i=0; $i<sizeof($request->team); $i++)
                {
                    $users = User::findOrFail($request->team[$i]);

                    if(Team::find($users->team_id)->notification == 1)
                        $data[] = $users->device_token;

                    $notify[] = ['type' => $type->id, 'sender_id' => $admin->id, 'receiver_id' => $users->id, 'notification_id' => $notification->id];
                }
                $teamStatus = 1;
            }
            else{
                $users = Team::where('game_id',$request->game)->where('notification',1)->get();
                foreach($users as $user)
                {
                    $data[]=$user->user->device_token;

                    $notify[] = ['type' => $type->id, 'sender_id' => $admin->id, 'receiver_id' => $user->user->id, 'notification_id' => $notification->id];
                }
            }

            AppNotification::insert($notify);

            $notification->team = $teamStatus;
            $notification->save();
            
            if($teamStatus == 1)
            {   
                for($i=0; $i<sizeof($request->team); $i++)
                {
                    $records[] =  ['notification_id' => $notification->id, 'user_id' => $request->team[$i]];
                }
                NotificationTeam::insert($records);
            }

            $pushNotification['type'] = $type;

            PushNotification::firebase($data, $request->title, $request->description, $pushNotification);
            
            return redirect()->route('notification')->with('success','Push Notification Send');
    }

    //Clear all notifications List
    public function destroy(){
        Notification::whereNotNull('id')->delete();
        return redirect()->back()->with('success','Notification Logs CLear Successfully');
    }
}
