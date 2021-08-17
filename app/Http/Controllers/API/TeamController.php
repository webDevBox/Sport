<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\ApiResponse;
use Validator;
use App\Models\User;
use App\Models\Game;
use App\Models\Days;
use App\Models\Team;
use App\Models\Venue;
use App\Models\PreferredArea;
use App\Models\TeamMember;
use App\Models\TeamAvailableDays;

class TeamController extends Controller
{

    //Teams List API
    public function teams(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'nullable|exists:teams,id'
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
            $last = Team::active()->orderBy('id','desc')->first();
            if(isset($last))
            {
                $counter = $last->id;
            }
            else
            {
                $data['total'] = 0;
                $data['teams'] = [];
                return response()->json(ApiResponse::success($data, 'MSG_NOT_FOUND'));
            }
        }

        $teams = Team::active()->orderBy('id','desc')->where('id', '<=' ,$counter)->limit(10)->get();
        $total = Team::active()->count();

        $data['total'] = $total;
        $data['teams'] = $teams;

        return response()->json(ApiResponse::success($data, 'TEAMS_LIST_MSG'));
    }

    //Get Team
    public function show(Request $request)
    {
        $team = Team::find($request->id);
        if(isset($team))
        {
            $data['team'] = $team;
            return response()->json(ApiResponse::success($data)); 
        }
        return response()->json(ApiResponse::error('TEAM_NOT_FOUND', 'RESPONSE_CODE_NOT_FOUND'));
    } 
    //Create Team API
    public function store(Request $request)
    {
        $existsError = ['exists' => 'Your Selected Day is Invalid'];
        
        $validator = Validator::make($request->all(),[
            'name'                       => 'required|unique:teams,name|string',
            'logo'                       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'game_id'                    => 'required|numeric',
            'distance'                   => 'required|numeric',
            'preferred_areas.*'          => 'Array',
            'preferred_areas.*.name'     => 'required|string',
            'preferred_areas.*.lat'      => 'required|string',
            'preferred_areas.*.lng'      => 'required|string',
            'team_members.*'             => 'Array',
            'team_members.*.name'        => 'required|string',
            'team_members.*.phone'       => 'required|string',
            'team_available_days'        => 'required|array|min:1',
            'team_available_days.*'      => 'exists:days,id'
        ],$existsError);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $user = auth()->user();

        $numOfPlayers = count($request->team_members);

        //Check Team Exist or Not
        if(Team::where('captain_id',$user->id)->count() == 0)
        {
            $path = null;
            if(isset($request->logo))
                {
                    $path = $request->logo->store('team');
                }
            //Creating Team
            $team = Team::create([
                'captain_id'      => $user->id,
                'name'            => $request->name,
                'logo'            => $path,
                'num_of_players'  => $numOfPlayers,
                'game_id'         => $request->game_id,
                'distance'        => $request->distance
            ]);

            //Update User Team_id
            User::where('id',$user->id)->update(['team_id'=>$team->id]);

            //Preferred Area
            $preferredAreas = $request->preferred_areas;

            foreach($preferredAreas as $preferredArea)
            {
                //Checking Either Preferred Area Exist in Venue or Not
                $venue = Venue::where('lat',$preferredArea['lat'])->where('lng',$preferredArea['lng'])->first();

                if(!isset($venue))
                {
                    //Add Venue
                    // $arr = explode(",", $preferredArea['address'], 2);
                    // $venueName = $arr[0];
                    $venue = Venue::create([
                        'name'     => $preferredArea['name'],
                        //'address'  => $preferredArea['address'],
                        'lat'      => $preferredArea['lat'],
                        'lng'     => $preferredArea['lng']
                    ]);
                }


                //Check Preferred Area Exist for Team or Not
                $venuePreferredArea = PreferredArea::where('team_id',$team->id)->where('venue_id',$venue->id)->first();

                if(!isset($venuePreferredArea))
                {
                    //Add Preferred Area
                    $preferredArea = new PreferredArea;
                    $preferredArea->team_id = $team->id;
                    $preferredArea->venue_id = $venue->id;
                    $preferredArea->save();
                }
            }

            //Team Members
            $teamMembers = $request->team_members;

            foreach($teamMembers as $teamMember)
            {
                //Add Team Members
                $newMember = new TeamMember;
                $newMember->name = $teamMember['name'];
                $newMember->phone = $teamMember['phone'];
                $newMember->team_id = $team->id;
                $newMember->save();
            }

            //Team Available Days
            $teamAvailableDays = $request->team_available_days;

            for($i=0; $i<sizeof($teamAvailableDays); $i++)
            {
                //Check Either Team Available Day Exist or Not
                $checkTeamAvailability = TeamAvailableDays::where('team_id',$team->id)->where('day_id',$teamAvailableDays[$i])->first();

                if(!isset($checkTeamAvailability))
                {
                    //Add Team Available Days
                    $teamAvailability = new TeamAvailableDays;
                    $teamAvailability->team_id = $team->id;
                    $teamAvailability->day_id = $teamAvailableDays[$i];
                    $teamAvailability->save();
                }
            }

            $newTeam = Team::find($team->id);

            $data['team'] = $newTeam;

            return response()->json(ApiResponse::success($data, 'TEAM_ADDED'));
        }

        return response()->json(ApiResponse::error('TEAM_ALREADY_EXIST', 'RESPONSE_CODE_NOT_ALLOWED'));

    }

    //Update Team Availability API
    public function availability()
    {
        $user = auth()->user();
        if($user->team_id != null)
        {
            $team = Team::find($user->team_id);

            if($team->availability == 'Available')
                $team->availability = 0;
            else
                $team->availability = 1;

            $team->save();

            $data['team'] = $team;

            return response()->json(ApiResponse::success($data, 'TEAM_AVAILABILITY_UPDATED'));
        }
        return response()->json(ApiResponse::error('TEAM_NOT_FOUND', 'RESPONSE_CODE_NOT_FOUND'));
    }

    //Update Team Members
    public function updateMember(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'team_members.*'             => 'Array',
            'team_members.*.name'        => 'required|string',
            'team_members.*.phone'       => 'required|string',
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $user = auth()->user();
        $team = Team::where('captain_id',$user->id)->first();

        TeamMember::where('team_id',$team->id)->delete();

        foreach($request->team_members as $teamMember)
        {
            $members[] = ['name' => $teamMember['name'], 'phone' => $teamMember['phone'], 'team_id' => $team->id];
        }
        
        TeamMember::insert($members);
        
        $data['team'] = $team;

        return response()->json(ApiResponse::success($data, 'TEAM_MEMBERS_UPDATED'));

    }

    //Update Team API Merge with profile Update
    // public function update(Request $request)
    // {
    //     $user = auth()->user();
    //     $team = Team::where('captain_id',$user->id)->first();

    //     $validator = Validator::make($request->all(),[
    //         'name'                       => 'required|string|unique:teams,name,' . $team->id,
    //         'distance'                   => 'required|numeric',
    //         'logo'                       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
    //         'preferred_areas.*'          => 'Array',
    //         'preferred_areas.*.address'  => 'required|string',
    //         'preferred_areas.*.lat'      => 'required|string',
    //         'preferred_areas.*.lng'      => 'required|string',
    //         'team_available_days.*'      => 'required|numeric'
    //     ]);

    //     $message = $validator->errors()->first();
    //     if ($validator->fails()) {
    //         return response()->json(ApiResponse::validation($message));
    //     }

    //     $path = $team->logo;
    //     if(isset($request->logo))
    //     {
    //         if($path != null)
    //         {
    //             Storage::delete($team->logo);
    //         }
    //         $path = $request->logo->store('team');
    //     }

    //     $availability = 0;
    //     if(count($request->team_available_days) > 0)
    //     {
    //         $availability = 1;
    //     }

    //     Team::where('captain_id',$user->id)->update([
    //         'name'          => $request->name,
    //         'logo'          => $path,
    //         'distance'      => $request->distance,
    //         'availability'  => $availability
    //     ]);

    //     TeamAvailableDays::where('team_id',$team->id)->delete();

    //     for($i=0; $i < sizeof($request->team_available_days); $i++)
    //     {
    //         $teamAvailableDays[] =  [
    //             'team_id' => $team->id,
    //             'day_id'  => $request->team_available_days[$i],
    //         ];
    //     }

    //     TeamAvailableDays::insert($teamAvailableDays);

    //     //Preferred Area
    //     $preferredAreas = $request->preferred_areas;

    //     PreferredArea::where('team_id',$team->id)->delete();

    //     foreach($preferredAreas as $preferredArea)
    //     {
    //         //Checking Either Preferred Area Exist in Venue or Not
    //         $venue = Venue::where('lat',$preferredArea['lat'])->where('lng',$preferredArea['lng'])->first();

    //         if(!isset($venue))
    //         {
    //             //Add Venue
    //             // $arr = explode(",", $preferredArea['address'], 2);
    //             $venue = Venue::create([
    //                 'name'     => $preferredArea['address'],
    //                 //'address'  => $preferredArea['address'],
    //                 'lat'      => $preferredArea['lat'],
    //                 'lng'     => $preferredArea['lng']
    //             ]);
    //         }

    //         $venuePreferredArea = PreferredArea::where('team_id',$team->id)->where('venue_id',$venue->id)->first();

    //         if(!isset($venuePreferredArea))
    //         {
    //             //Add Preferred Area
    //             PreferredArea::create([
    //                 'team_id'  => $team->id,
    //                 'venue_id' => $venue->id
    //             ]);
    //         }
            
    //     }

    //     $data['team'] = $team;

    //     return response()->json(ApiResponse::success($data, 'TEAM_UPDATED'));
    // }
}
