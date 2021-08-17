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

class VenueController extends Controller
{
    public function allVenues(Request $request)
    {
        $venues = Venue::orderBy('id','desc')->get();
        if ($request->ajax()) {
            return Datatables::of($venues) 
                ->addColumn('name', function($name){

                    return $name->name;
                })
                // ->addColumn('address', function($address){

                //     return $address->address;
                // })
               
                ->rawColumns(['name'])
                ->make(true);     
        }
        return view('admin.venues.index');
    }
}
