<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'API'], function(){
    Route::post('/login', 'AuthController@login');
    Route::post('/signup', 'AuthController@signup');
    Route::post('/social-signup', 'AuthController@socialSignup');
    Route::post('/social-login', 'AuthController@socialLogin');
    Route::post('/forgot-password', 'AuthController@forgotPassword');

    Route::middleware('guest:api')->group(function (){
        Route::get('/home', 'HomeController@dashboard');
        Route::get('user', 'HomeController@userById');
        Route::get('/allTeams', 'TeamController@teams');
        Route::get('/challenge-list', 'ChallengeController@list');
        Route::get('/match-list', 'MatchController@list');
        Route::post('/get-challenge-comment', 'ChallengeController@getComment');
        Route::post('/get-match-comment', 'MatchController@getComment');
    });
//
   Route::middleware('auth:api')->group(function () {
       Route::post('/verify', 'AuthController@verify');
       Route::get('/resend-otp', 'AuthController@resendOtp');
       Route::post('/update-profile', 'AuthController@profileUpdate');
       Route::post('/update-password', 'AuthController@passwordUpdate');
       Route::get('/games', 'HomeController@games');
       Route::get('/days', 'HomeController@days');
       Route::post('/upload-image', 'HomeController@uploadImage');
       Route::get('/logout', 'AuthController@logout');

       Route::group(['prefix' => 'teams'], function (){
          Route::get('/show', 'TeamController@show');
          Route::post('/create', 'TeamController@store');
          Route::post('/update', 'TeamController@update');
          Route::post('/update-member', 'TeamController@updateMember');
          Route::get('/availability', 'TeamController@availability');
       });

       Route::group(['prefix' => 'challenge'], function(){
           Route::get('/', 'ChallengeController@index');
           Route::get('/my-challenge', 'ChallengeController@myChallenge');
           Route::get('/request-challenge', 'ChallengeController@requestChallenge');
           Route::post('/create', 'ChallengeController@store');
           Route::post('/update-status', 'ChallengeController@update');
           Route::post('/challenge-like', 'ChallengeController@like');
           Route::post('/challenge-comment', 'ChallengeController@comment');
       });

       Route::group(['prefix' => 'matches'],function(){
        Route::get('/my-match', 'MatchController@myMatch');
        Route::post('/match-like', 'MatchController@like');
        Route::post('/match-comment', 'MatchController@comment');
       });

       Route::group(['prefix' => 'notification'], function(){
            Route::get('/', 'AppNotificationController@index');
            Route::get('/update', 'AppNotificationController@update');
            Route::get('/delete', 'AppNotificationController@destroy');
            Route::get('/status', 'AppNotificationController@notification');
       });

       Route::group(['prefix' => 'chat'], function(){
        Route::post('/store', 'ChatController@store');
       });
       
       Route::group(['prefix' => 'feedback'], function(){
        Route::post('/store', 'FeedbackController@store');
       });

   });
});
