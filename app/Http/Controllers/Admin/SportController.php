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
use DataTables;

class SportController extends Controller
{
    //Active Sports List
    public function index(Request $request){
        $activeSports = Game::orderBy('id','desc')->get();
        if ($request->ajax()) {
            return Datatables::of($activeSports)
                
                ->addColumn('name', function($name){

                    $logo = '<img class="rounded-circle mr-2" alt="" width="40" height="40" src="'.asset('files/sport/no-img.png') .'" /> '.$name->name;

                    if ($name->logo != null){
                    $logo = '<img class="rounded-circle mr-2" alt="" width="40" height="40" src='.asset('files/'.$name->logo).'> '.$name->name; }
                    
                    return $logo;
                })
                ->addColumn('action', function($row){

                        $btn = '<a href="sport/edit/'.$row->id.'" class="btn btn-primary btn-sm btn-rounded ">Edit</a>';
    
                        return $btn;
                })
                ->addColumn('status',function ($inquiry) {
                    if ($inquiry->status == 1) return '<label class="switch"> <input onchange="myFunction('.$inquiry->id.')" type="checkbox" checked> <span class="slider round"></span> </label>';
                    if ($inquiry->status == 0) return '<label class="switch"> <input onchange="myFunction('.$inquiry->id.')" type="checkbox"> <span class="slider round"></span> </label>';
                   })
                ->rawColumns(['action', 'name', 'status'])
                ->make(true);     
        }
        return view('admin.sports.sportList');
    }
   
   //InActive Sports List is not using yet
    // public function inActiveSport(Request $request){
    //     if ($request->ajax()) {
    //         $inActiveSports = Game::where('status',2)->orderBy('id','desc')->get();
    //         return Datatables::of($inActiveSports)
    //             
    //             ->addColumn('name', function($name){

    //                 if ($name->logo != null)
    //                 $btn = '<center> <img class="rounded-circle mr-2" alt="" width="40" src='.asset('files/'.$name->logo).'> '.$name->name.' </center>';
    //                 else
    //                 $btn = '<center> <img class="rounded-circle mr-2" alt="" width="40" src="'.asset('files/sport/no-img.png') .'" /> '.$name->name.' </center>';
    
    //                 return $btn;
    //             })
    //             ->addColumn('action', function($row){

    //                     $btn = '<center><a href="sport/edit/'.$row->id.'" class="btn btn-primary">Edit</a></center>';
    
    //                     return $btn;
    //             })
    //             ->rawColumns(['action', 'name'])
    //             ->make(true);     
    //     }
    //     return view('admin.sports.sportList');
    // }

    //Challange List
    public function challange(Request $request)
    {
        $challanges = Challange::orderBy('id','desc')->get();
        if ($request->ajax()) {
            return Datatables::of($challanges)
                
                ->addColumn('Challanger', function($challanger){

                    if ($challanger->challenger->logo != null)
                        return '<img class="rounded-circle mr-2" alt="" width="40" height="40" src='.asset('files/'.$challanger->challenger->logo).'> '.$challanger->challenger->name;
                    else
                        return '<img class="rounded-circle mr-2" alt="" width="40" height="40" src="'.asset('files/sport/no-img.png') .'" /> '.$challanger->challenger->name;       
  
                })
                ->addColumn('Opponent', function($opponent){

                    if ($opponent->opponent->logo != null)
                        return '<img class="rounded-circle mr-2" alt="" width="40" height="40" src='.asset('files/'.$opponent->opponent->logo).'> '.$opponent->opponent->name;
                    else
                        return '<img class="rounded-circle mr-2" alt="" width="40" height="40" src="'.asset('files/sport/no-img.png') .'" /> '.$opponent->opponent->name;

                })
                ->addColumn('Message', function($message){

                    return $message->message;
                        
                })
                ->addColumn('Proposed Time', function($proposed){

                    return $proposed->proposed_time;
                        
                })
                ->addColumn('Venue', function($venue){

                    return $venue->venue->name;
                        
                })
                ->addColumn('Status', function($Status){

                    // return '<span class=" badge badge-pill badge-primary">'.$Status->status.'</span>';
                    
                    
                   if ($Status->status == 'pending') return '<span class=" badge badge-pill badge-warning p-2 text-uppercase">'.$Status->status.'</span>';
                   if ($Status->status == 'accepted') return '<span class=" badge badge-pill badge-success p-2 text-uppercase">'.$Status->status.'</span>';
                   if ($Status->status == 'rejected') return '<span class=" badge badge-pill badge-danger p-2 text-uppercase">'.$Status->status.'</span>' ;

                })
                ->addColumn('Game', function($game){

                    if ($game->game->logo != null)
                        return '<img class="rounded-circle mr-2" alt="" width="40" height="40" src='.asset('files/'.$game->game->logo).'> '.$game->game->name;
                    else
                        return '<img class="rounded-circle mr-2" alt="" width="40" height="40" src="'.asset('files/sport/no-img.png') .'" /> '.$game->game->name;
                    
                })
                ->rawColumns(['Challanger', 'Opponent', 'Message', 'Proposed Time', 'Venue', 'Game', 'Status'])
                ->make(true);     
        }
        return view('admin.sports.challange');
    }
    
    
    //Match List
    public function match(Request $request)
    {
        $matches = Match::orderBy('id','desc')->get();
        
        if ($request->ajax()) {
            return Datatables::of($matches)
                
                ->addColumn('Challanger', function($challanger){

                    if ($challanger->challenge->challenger->logo != null)
                        return ' <img class="rounded-circle mr-2" alt="" width="40" height="40" src='.asset('files/'.$challanger->challenge->challenger->logo).'> '.$challanger->challenge->challenger->name.' ';
                    else
                        return ' <img class="rounded-circle mr-2" alt="" width="40" height="40" src="'.asset('files/sport/no-img.png') .'" /> '.$challanger->challenge->challenger->name.' ';

                })
                ->addColumn('Opponent', function($opponent){

                    if ($opponent->challenge->opponent->logo != null)
                        return ' <img class="rounded-circle mr-2" alt="" width="40" height="40" src='.asset('files/'.$opponent->challenge->opponent->logo).'> '.$opponent->challenge->opponent->name.' ';
                    else
                        return ' <img class="rounded-circle mr-2" alt="" width="40" height="40" src="'.asset('files/sport/no-img.png') .'" /> '.$opponent->challenge->opponent->name.' ';

                })
                ->addColumn('Time', function($proposed){

                    return $proposed->challenge->proposed_time;
                        
                })
                ->addColumn('Venue', function($venue){

                    return $venue->challenge->venue->name;
                        
                })
                ->addColumn('Game', function($game){

                    if ($game->challenge->game->logo != null)
                        return ' <img class="rounded-circle mr-2" alt="" width="40" height="40" src='.asset('files/'.$game->challenge->game->logo).'> '.$game->challenge->game->name.' ';
                    else
                        return ' <img class="rounded-circle mr-2" alt="" width="40" height="40" src="'.asset('files/sport/no-img.png') .'" /> '.$game->challenge->game->name.' ';

                })
                ->rawColumns(['Challanger', 'Opponent', 'Time', 'Venue', 'Game'])
                ->make(true);     
        }
        return view('admin.sports.match');
    }
    
    //Teams List
    public function allTeams(Request $request)
    {
        $teams = Team::orderBy('id','desc')->get();
        if ($request->ajax()) {
            return Datatables::of($teams)
                
                ->addColumn('Name', function($name){

                    if ($name->logo != null)
                    return ' <img class="rounded-circle mr-2" alt="" width="40" height="40" src='.asset('files/'.$name->logo).'> '.$name->name;
                    else
                    return ' <img class="rounded-circle mr-2" alt="" width="40" height="40" src="'.asset('files/sport/no-img.png') .'" /> '.$name->name; 
                })
                ->addColumn('Captain', function($captain){

                    return $captain->user->first_name.' '.$captain->user->last_name;   
                })
                ->addColumn('Players', function($players){

                    return $players->num_of_players;
                        
                })
                ->addColumn('Distance', function($distance){

                    return $distance->distance.' km';
                        
                })
                ->addColumn('Availability', function($Availability){

                    return $Availability->availability;
                        
                })
                ->addColumn('Game', function($game){

                    if ($game->game->logo != null)
                    return ' <img class="rounded-circle mr-2" alt="" width="40" height="40" src='.asset('files/'.$game->game->logo).'> '.$game->game->name.' ';
                    else
                    return ' <img class="rounded-circle mr-2" alt="" width="40" height="40" src="'.asset('files/sport/no-img.png') .'" /> '.$game->game->name.' ';
                        
                })
                ->rawColumns(['Name', 'Captain', 'Players', 'Distance', 'Availability', 'Game'])
                ->make(true);     
        }
        return view('admin.sports.team');
    }

    //Store Sport
    public function create(Request $request)
    {
        $dimensionsError = [
            'dimensions' => 'Logo dimensions must be 400x800 or less',
        ];

        $this->validate($request,[
            'name'   => 'required|string|max:255|unique:games,name',
            'status' => 'required|numeric',
            'logo'   => 'image|mimes:jpeg,png,jpg,gif,svg|dimensions:max_width=400,max_height=800|max:2048'
        ],$dimensionsError);
            if(isset($request->logo))
            {
                $path = $request->logo->store('sport');
            }
            else{
                $path = null;
            }
            $sport = Game::create([
                'name'   => $request->name,
                'status' => $request->status,
                'logo'   => $path
            ]);

            if($sport)
            {
                return redirect()->route('sport')->with('success','Sport Added Successfully');
            }
            return redirect()->back()->with('error','Sport Not Added');
    }

    public function edit($id)
    {
        $sport = Game::findOrFail($id);
        return view('admin.sports.editor')->with('sport',$sport);
    }
    
    public function createSport()
    {
        return view('admin.sports.creator');
    }
    
    public function breadCrum()
    {
        return redirect()->route('sport');
    }

    //Update Sport
    public function update(Request $request)
    {
            $sport = Game::findOrFail($request->id);
            $dimensionsError = [
                'dimensions' => 'Logo dimensions must be 400x800 or less',
            ];
                $this->validate($request,[
                    'id'        => 'required|numeric',
                    'name'      => 'required|string|max:255|unique:games,name,' . $sport->id,
                    'status'    => 'required|numeric',
                    'logo'      => 'image|mimes:jpeg,png,jpg,gif,svg|dimensions:max_width=400,max_height=800|max:2048'
                ],$dimensionsError);

                if($request->image == 'del')
                {
                    if($sport->logo != null)
                    {
                    Storage::delete($sport->logo);
                    }
                    $sport->logo = null;
                }

                if(isset($request->logo))
                {
                    if($sport->logo != null)
                    {
                        Storage::delete($sport->logo);
                    }
                    $path = $request->logo->store('sport');
                    $sport->logo = $path;
                }

                $sport->name = $request->name;
                $sport->status = $request->status;
                $sport->save();

                if($sport)
                {
                    return redirect()->back()->with('success','Sport Edit Successfully');
                }
                return redirect()->back()->with('error','Sport Not Edit');
    }

    //Update Status
    public function sportStatus(Request $request)
    {
        $sport = Game::findOrFail($request->id);
        
         $sport->status = !$sport->status;
        
        $sport->save();

        return response()->json(['success'=>'Sport status change successfully.']);
    }


}
