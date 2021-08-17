<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\ApiResponse;
use Validator;
use App\Models\User;
use App\Models\Role;
use App\Models\Team;
use App\Models\Venue;
use App\Models\TeamAvailableDays;
use App\Models\PreferredArea;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Jobs\SendEmailJob;
use App\Jobs\SendSmsJob;
use Twilio\Rest\Client;
use DB;

class AuthController extends Controller
{
    //Login API
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value'         => 'required',
            'column'        => 'required',
            'password'      => 'required',
            'device_token'  => 'required'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $role = Role::where('name','user')->first();
        
        if(Auth::attempt([$request->column => $request->value, 'password' => $request->password, 'role_id' => $role->id])){
            $user  = Auth::user();

            if($user->otp != null)
            {
                return response()->json(ApiResponse::error("UNVERIFIED_USER", 'STATUS_EMAIL_NOT_VERIFIED')); 
            }

            $token =  $user->createToken('Sports App')->accessToken;
            $data['user'] = $user;

            $user->device_token = $request->device_token;
            $user->save();

            return response()->json(ApiResponse::successWithToken($data, 'MSG_LOGGED_IN', $token));
        }

        return response()->json(ApiResponse::error("ERROR_INVALID_CREDENTIALS", 'RESPONSE_CODE_INVALID_CREDENTIALS'));
    }

    //SignUp Api
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'first_name'  => 'required',
            'last_name'   => 'required',
            'email'       => 'required_if:phone,""',
            'phone'       => 'required_if:email,""',
            'password'    => 'required|min:6'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        if(isset($request->email) && isset($request->phone))
        {
            return response()->json(ApiResponse::error('ERROR_BOTH_NOT_ALLOW'));
        }


        if($request->email != null && User::where('email',$request->email)->where('provider',null)->count() > 0)
         {
             return response()->json(ApiResponse::error('ERROR_EMAIL_EXIST'));
         }
         
         if($request->phone != null && User::where('phone',$request->phone)->where('provider',null)->count() > 0)
         {
             return response()->json(ApiResponse::error('ERROR_PHONE_EXIST'));
         }

        $otp = rand(111111,999999);

        $role = Role::where('name','user')->first();

        $user = User::create([
            'first_name'   => $request->first_name,
            'last_name'    => $request->last_name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'password'     => bcrypt($request->password),
            'role_id'      => $role->id,
            'otp'          => $otp,
            'device_token' => $request->device_token
        ]);

        $data['user'] = $user;
        $token =  $user->createToken('Sports App')->accessToken;

        if($user)
        {
            if(isset($request->email))
            {
                $details=[
                    'value'=>$user->otp,
                    'email'=>$user->email,
                    'message'=>'Your OTP is:'
                ];
                dispatch(new SendEmailJob($details));
            }
            else{ 
                $details=[
                    'otp'=>$user->otp,
                    'phone'=>$user->phone,
                    'message' => 'Your Varification Code is:'
                ];
                dispatch(new SendSmsJob($details));    
            }

            return response()->json(ApiResponse::successWithToken($data,'VERIFY_YOUR_ACCOUNT', $token));
        }

        return response()->json(ApiResponse::error());
    }

    //Verification API
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'otp' => 'required|exists:users,otp'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::notFound('WRONG_OTP'));
        }
        
        $user = auth()->user();

        if($request->otp == $user->otp)
        {
            $user->otp = null;
            $user->save();

            $data['user'] = $user;
            $data['team'] = null;

            $token =  $user->createToken('Sports App')->accessToken;

            return response()->json(ApiResponse::successWithToken($data,'SIGN_UP_SUCCESS_MESSAGE', $token));
        }

        return response()->json(ApiResponse::error('WRONG_OTP', 'RESPONSE_CODE_INVALID_CREDENTIALS'));
    }

    //Resend Verification Code API
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required|exists:users,id|numeric'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $otp = rand(111111,999999);

        $user = User::find($request->id);
        $user->otp = $otp;
        $user->save();

        if($user->email != null)
            {
                $details=[
                    'value'=>$user->otp,
                    'email'=>$user->email,
                    'message'=>'Your OTP is:'
                ];
                dispatch(new SendEmailJob($details));
            }
            else{
                    $accountSid = config('services.twilio')['TWILIO_ACCOUNT_SID'];
                    $authToken  = config('services.twilio')['TWILIO_AUTH_TOKEN'];
                    $fromNumber = config('services.twilio')['TWILIO_FROM_NUMBER'];
                    $otpMsgBody = config('constants.otp')['MESSAGE'];

                    $client = new Client($accountSid, $authToken);

                    try
                    {
                        $client->messages->create(
                            // the number you'd like to send the message to
                            $user->phone,
                        array(
                                'from' => $fromNumber,
                                'body' => $otpMsgBody.$otp.'.',
                        )
                        );
                    }
                    catch (Exception $e)
                    {
                        return response()->error('OTP_NOT_SEND');
                    }
            }

        $data['user'] = $user->otp;

        return response()->json(ApiResponse::success($data, 'OTP_SEND'));
    }

    //Profile Update API
    public function profileUpdate(Request $request)
    {
        $user = auth()->user();
        $team = Team::where('captain_id',$user->id)->first();

        $existsError = ['exists' => 'Your Selected Day is Invalid'];

        $validator = Validator::make($request->all(),[
            'first_name'                 => 'required|max:255|string',
            'last_name'                  => 'required|max:255|string',
            'name'                       => 'required|string|unique:teams,name,' . $team->id,
            'distance'                   => 'required|numeric',
            'logo'                       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'preferred_areas.*'          => 'Array',
            'preferred_areas.*.name'     => 'required|string',
            'preferred_areas.*.lat'      => 'required|string',
            'preferred_areas.*.lng'      => 'required|string',
            'team_available_days'        => 'required|array|min:1',
            'team_available_days.*'      => 'exists:days,id'
        ],$existsError);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->save();

        $path = $team->logo;
        if(isset($request->logo))
        {
            if($path != null)
            {
                Storage::delete($team->logo);
            }
            $path = $request->logo->store('team');
        }

        $availability = 0;
        if(count($request->team_available_days) > 0)
        {
            $availability = 1;
        }

        Team::where('captain_id',$user->id)->update([
            'name'          => $request->name,
            'logo'          => $path,
            'distance'      => $request->distance,
            'availability'  => $availability
        ]);

        TeamAvailableDays::where('team_id',$team->id)->delete();

        for($i=0; $i < sizeof($request->team_available_days); $i++)
        {
            $teamAvailableDays[] =  [
                'team_id' => $team->id,
                'day_id'  => $request->team_available_days[$i],
            ];
        }

        TeamAvailableDays::insert($teamAvailableDays);

        //Preferred Area
        $preferredAreas = $request->preferred_areas;

        PreferredArea::where('team_id',$team->id)->delete();

        foreach($preferredAreas as $preferredArea)
        {
            //Checking Either Preferred Area Exist in Venue or Not
            $venue = Venue::where('lat',$preferredArea['lat'])->where('lng',$preferredArea['lng'])->first();

            if(!isset($venue))
            {
                //Add Venue
                // $arr = explode(",", $preferredArea['address'], 2);
                $venue = Venue::create([
                    'name'     => $preferredArea['name'],
                    //'address'  => $preferredArea['address'],
                    'lat'      => $preferredArea['lat'],
                    'lng'     => $preferredArea['lng']
                ]);
            }

            $venuePreferredArea = PreferredArea::where('team_id',$team->id)->where('venue_id',$venue->id)->first();

            if(!isset($venuePreferredArea))
            {
                //Add Preferred Area
                PreferredArea::create([
                    'team_id'  => $team->id,
                    'venue_id' => $venue->id
                ]);
            }
            
        }

        $captain = auth()->user();
        $updatedTeam = Team::where('captain_id',$captain->id)->first();
        $data['team'] = $updatedTeam;

        return response()->json(ApiResponse::success($data, 'PROFILE_UPDATED'));
    }

    //Password Update API
    public function passwordUpdate(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'old_password' => 'required|min:6',
            'password'     => 'required|min:6'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $user = auth()->user();

        if(Hash::check($request->old_password, $user->password))
        {
            $user->password = bcrypt($request->password);
            $user->save();
    
            $data['user'] = $user;

            return response()->json(ApiResponse::success($data, 'PASSWORD_UPDATED'));
        }
        else
        {
            return response()->json(ApiResponse::error('WRONG_PASSWORD', 'RESPONSE_CODE_INVALID_CREDENTIALS'));
        }
    }
   
    //Forgot Password API
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value'         => 'required',
            'column'        => 'required'
        ]);

        $message = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json(ApiResponse::validation($message));
        }

        $user = User::where($request->column,$request->value)->first();

        if(isset($user) && $user->provider == null)
        {
            $password = rand(100000,999999);
            $user->password = bcrypt($password);
            $user->save();


            if($request->column == 'email')
            {
                $details=[
                    'value'=>$password,
                    'email'=>$user->email,
                    'message'=>'Your Auto generated password is:'
                ];
                dispatch(new SendEmailJob($details));
            }
            else{
                    $details=[
                        'otp'     => $password,
                        'phone'   => $user->phone,
                        'message' => 'Your Auto generated password is:'
                    ];
                    dispatch(new SendSmsJob($details));    
            }


            $data['user'] = $user;

            return response()->json(ApiResponse::success($data, 'PASSWORD_SEND'));
        }
      
        return response()->json(ApiResponse::error('USER_NOT_EXIST', 'RESPONSE_CODE_NOT_FOUND'));
    }

    // //Logout API
    public function logout(Request $request)
    {
        $user = auth()->user();

        if(isset($user))
        {
            User::where('id',$user->id)->update(['device_token' => null]);

            $user->token()->revoke();
        
            return response()->json(ApiResponse::success(null,'LOGGED_OUT'));
        }

        return response()->json(ApiResponse::error('USER_NOT_LOGGED_IN', 'RESPONSE_CODE_NOT_FOUND'));
        
        
    }


     //Social Signup
     public function socialSignup(Request $request)
     {
         $validator = Validator::make($request->all(),[
             'first_name'      => 'required|string',
             'last_name'       => 'required|string',
             'email'           => 'required_if:phone,""',
             'phone'           => 'required_if:email,""',
             'provider_id'     => 'required|string',
             'provider'        => 'required|string',
             'device_token'    => 'required',
         ]);

         $query = User::providerWrapper($request->provider);
 
         $message = $validator->errors()->first();
         if ($validator->fails()) 
            {
                return response()->json(ApiResponse::validation($message));
            }

         if($request->email != null && User::where('email',$request->email)->where('provider',$query)->count() > 0)
            {
                return response()->json(ApiResponse::error('ERROR_EMAIL_EXIST'));
            }
         
         if($request->phone != null && User::where('phone',$request->phone)->where('provider',$query)->count() > 0)
            {
                return response()->json(ApiResponse::error('ERROR_PHONE_EXIST'));
            }

         if(isset($request->email) && isset($request->phone))
            {
                return response()->json(ApiResponse::error('ERROR_BOTH_NOT_ALLOW'));
            }
         
         $role = Role::where('name','user')->first();

         $user = User::create([
             'first_name'   => $request->first_name,
             'last_name'    => $request->last_name,
             'email'        => $request->email,
             'phone'        => $request->phone,
             'role_id'      => $role->id,
             'provider'     => $query,
             'provider_id'  => $request->provider_id,
             'device_token' => $request->device_token
         ]);
 
         $data['user'] = $user;
         $user->provider = User::getStatusAttribute($user->provider);
 
         if($user)
         {
             $token =  $user->createToken('Sports App')->accessToken;
 
             return response()->json(ApiResponse::successWithToken($data, 'SIGN_UP_SUCCESS_MESSAGE', $token));
         }
 
         return response()->json(ApiResponse::error());
     }
 
     //Social Login
     public function socialLogin(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'value'         => 'required',
             'column'        => 'required',
             'provider'      => 'required|string',
             'provider_id'   => 'required|string|exists:users,provider_id',
             'device_token'  => 'required|string'
         ]);
 
         $message = $validator->errors()->first();
         if ($validator->fails()) {
             return response()->json(ApiResponse::validation($message));
         }

         $role = Role::where('name','user')->first();
 
         $user = User::where($request->column,$request->value)->where('provider',User::providerWrapper($request->provider))
         ->where('provider_id',$request->provider_id)->where('role_id',$role->id)->first();
         
         if(isset($user))
         {
             $token = $user->createToken('Sports App')->accessToken;
 
             $user->device_token = $request->device_token;
             $user->save();
             $user->provider = User::getStatusAttribute($user->provider);
             $data['user'] = $user;
 
             return response()->json(ApiResponse::successWithToken($data, 'MSG_LOGGED_IN', $token));
         }
 
         return response()->json(ApiResponse::error("ERROR_INVALID_CREDENTIALS", 'RESPONSE_CODE_INVALID_CREDENTIALS'));
     }
}
