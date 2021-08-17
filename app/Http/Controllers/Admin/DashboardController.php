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
use Validator;
use App\Models\User;
use App\Models\Game;
use App\Models\Days;
use App\Models\Team;
use App\Models\Venue;
use DataTables;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $latestChallanges = Challange::orderBy('id','desc')->limit(5)->get();
        $latestMatches    = Match::orderBy('id','desc')->limit(5)->get();
        $recentTeams      = Team::orderBy('id','desc')->limit(10)->get();
        $challanges       = Challange::count();
        $allVanues        = Venue::get();
        $matches          = Match::count();
        $venues           = Venue::count();
        $teams            = Team::count();
    	return view('admin.dashboard.dashboard',compact(['teams','venues','challanges','matches','latestChallanges','latestMatches','recentTeams','allVanues']));
    }
}
