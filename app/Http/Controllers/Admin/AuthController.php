<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|string|max:191',
            'password' => 'required|min:3',
        ]);

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput($request->all());
        }

        if (Auth::guard('admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            return redirect()->route('dashboard');
        } else {      
            return redirect()->back()->with('error' , "Incorrect Email or Password!");
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect()->route('showAdminLogin');
    }

    //Edit Profile Page
    public function profilePage()
    {
       $user = Auth::guard('admin')->user();
       return view('admin.profile')->with('user',$user);
    }

    //Update Profile
    public function editProfile(Request $request)
    {
        $dimensionsError = [
            'dimensions' => 'Image dimensions must be 400x800 or less',
        ];
        $this->validate($request,[
            'firstName' => 'required|string|max:255',
            'lastName'  => 'required|string|max:255',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|dimensions:max_width=400,max_height=800|max:5120',
        ],$dimensionsError);

        $user = Auth::guard('admin')->user();

        $user->first_name = $request->firstName;
        $user->last_name = $request->lastName;

        if(isset($request->oldPassword))
        {
            $this->validate($request,[
                'oldPassword'  => 'required|min:6',
                'newPassword'  => 'required|min:6'
            ]);
            if(Hash::check($request->oldPassword, $user->password))
            {
                $user->password = Hash::make($request->newPassword);
            }
            else{
                return redirect()->back()->with('error','Wrong Old Password');
            }
        }
        
        if(isset($request->image))
        {
            if($user->image == null)
            {
                $path = $request->image->store('profile');
                $user->image = $path;
            }
            else{
                Storage::delete($user->image);
                $path = $request->image->store('profile');
                $user->image = $path;
            }
        }
        $user->save();
        if($user->save())
        {
            return redirect()->back()->with('success','Profile Updated');
        }
        return redirect()->back()->with('error','Profile Not Updated');
    }
}
