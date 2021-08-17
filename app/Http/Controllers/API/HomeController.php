<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Challange;
use App\Models\ChallangeLike;
use App\Models\MatchesLike;
use App\Models\Match;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\ApiResponse;
use Validator;
use App\Models\User;
use App\Models\Game;
use App\Models\Days;
use App\Models\Team;

class HomeController extends Controller
{
    public $per_page_record = 0;
    public function __construct()
    {
        $this->per_page_record = config('constants.PAGINATION_CONSTANTS')['KEY_RECORD_PER_PAGE'];
    }

    // Home Screen
    public function dashboard(Request $request)
    {
        $data = array();
        $data['challenges'] = Challange::latest()->take($this->per_page_record)->get();
        if(isset($request->id))
        {
            foreach($data['challenges'] as $record)
            {
                $check = ChallangeLike::where('challange_id',$record->id)->where('user_id',$request->id)->first();
                if(isset($check))
                    $record['isLike'] = true;
                else
                    $record['isLike'] = false;
            }
        }
        else
        {
            foreach($data['challenges'] as $record)
            {
                $record['isLike'] = false;
            } 
        }
        $data['teams']      = Team::active()->latest()->take($this->per_page_record)->get();
        $data['matchs']     = Match::latest()->take($this->per_page_record)->get();

        if(isset($request->id))
        {
            foreach($data['matchs'] as $match)
            {
                $checkMatch = MatchesLike::where('match_id',$match->id)->where('user_id',$request->id)->first();
                if(isset($checkMatch))
                    $match['isLike'] = true;
                else
                    $match['isLike'] = false;
            }
        }
        else
        {
            foreach($data['matchs'] as $match)
            {
                $match['isLike'] = false;
            } 
        }

        $data['days']     = Days::get();

        return response()->json(ApiResponse::success($data, 'DASHBOARD_DATA_MSG'));
    }
    //Games List API
    public function games()
    {
        $games = Game::active()->get();
        $data['games'] = $games;

        return response()->json(ApiResponse::success($data, 'GAME_LIST_MSG'));
    }


    //Days List API
    public function days()
    {
        $days = Days::active()->get();
        $data['days'] = $days;

        return response()->json(ApiResponse::success($data, 'DAYS_LIST_MSG'));
    }

    //Upload Image
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $user = auth()->user();

            $team = Team::where('captain_id',$user->id)->first();

            if(isset($team))
            {   
                if($team->logo == null)
                {
                    $path = $request->logo->store('team');
                    $team->logo = $path;
                    $team->save();
                }
                else{
                    Storage::delete($team->logo);
                    $path = $request->logo->store('team');
                    $team->logo = $path;
                    $team->save();
                }

                $data['team'] = $team;
                return response()->json(ApiResponse::success($data, 'IMAGE_UPLOADED'));
            }

            return response()->json(ApiResponse::error('TEAM_NOT_EXIST', 'RESPONSE_CODE_NOT_FOUND'));
    }

    public function userById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric|exists:users,id'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $user = User::find($request->user_id);
        $data['user'] = $user;

        return response()->json(ApiResponse::success($data, 'MSG_FOUND'));
    }
}
