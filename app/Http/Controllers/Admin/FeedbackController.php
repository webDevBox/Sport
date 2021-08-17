<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Challange;
use App\Models\Feedback;
use App\Models\Venue;
use App\Models\Match;
use App\ApiResponse;
use App\Models\User;
use App\Models\Game;
use App\Models\Days;
use DataTables;
use Validator;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Feedback = Feedback::latest()->get();

        if ($request->ajax()) {
            return Datatables::of($Feedback)
                
                ->addColumn('User', function($User){

                    return $User->user->first_name.' '.$User->user->last_name;
                })
                ->addColumn('Message', function($Message){

                    return $Message->message;
                })
                ->addColumn('Created',function ($Created) {
                    return $Created->created_at;
                })
                ->rawColumns(['User', 'Message', 'created',])
                ->make(true);     
        }
        return view('admin.feedback.index');
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
}
