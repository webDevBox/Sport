<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('admin.login');
// });

Route::get('/', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('adminDashboard');
    }
    return view('admin.login');
})->name('showAdminLogin');

Route::post('/admin/login', 'Admin\AuthController@login')->name('AdminLogin');

Route::group(['prefix' => 'admin', 'middleware' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/adminDashboard', 'DashboardController@index')->name('adminDashboard');

    Route::group(['prefix' => 'sport'], function (){
        Route::get('/', 'SportController@index')->name('sport');
        // Route::get('/inActiveSport', 'SportController@inActiveSport')->name('inActiveSport');
        Route::get('/createSport', 'SportController@createSport')->name('createSport');
        Route::post('/create', 'SportController@create')->name('storeSport');
        Route::post('/Update-profile', 'AuthController@editProfile')->name('editProfile');
        Route::get('/edit', 'SportController@breadCrum');
        Route::get('/edit/{id}', 'SportController@edit');
        Route::post('/Update', 'SportController@update')->name('editSport');
        Route::get('/sportStatus', 'SportController@sportStatus')->name('sportStatus');
     });

    
    Route::get('/profile', 'AuthController@profilePage')->name('profilePage');
    Route::get('/match', 'SportController@match')->name('match');
    Route::get('/challange', 'SportController@challange')->name('challange');
    Route::get('/teams', 'SportController@allTeams')->name('allTeams');
    Route::get('/venues', 'VenueController@allVenues')->name('allVenues');

    Route::group(['prefix' => 'notification'],function(){
        Route::get('/', 'NotificationController@index')->name('notification');
        Route::get('/create', 'NotificationController@create')->name('create');
        Route::get('/delete', 'NotificationController@destroy')->name('delete');
        Route::post('/store', 'NotificationController@store')->name('store');
        Route::get('/getTeams', 'NotificationController@getTeams')->name('getTeams');
    });
    
    Route::group(['prefix' => 'feedback'],function(){
        Route::get('/', 'FeedbackController@index')->name('feedback');
    });
    

    //Admin logout
    Route::get('/logout', 'AuthController@logout')->name('logout');
});

Route::get('{route}', 'Frontend\PageController@routePage');