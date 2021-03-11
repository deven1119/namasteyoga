<?php

use Illuminate\Http\Request;

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
Use App\Rest;

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');
Route::post('userList', 'UserController@getUserList');
Route::post('getUserByCity', 'UserController@getUserByCity');

Route::post('eventList', 'EventController@getEventList');

Route::post('cityList', 'CountryController@getCityList');
Route::post('otpVerification', 'UserController@otpVerification');

Route::post('notifyMe', 'UserController@notifyMe');
Route::post('resentRegisterOtp', 'UserController@resentRegisterOtp');

Route::post('setLatestVersion', 'VersionController@setLatestVersion');
Route::post('getLatestVersion', 'VersionController@getLatestVersion');
Route::post('deleteVersion', 'VersionController@deleteVersion');

Route::post('forgotPassword', 'UserController@forgotPassword'); 
Route::post('verifyForgotPassword', 'UserController@verifyForgotPassword'); 

Route::post('addRating', 'RatingController@addRating');  


Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', 'UserController@getAuthenticatedUser');        
    Route::post('addEvent', 'EventController@addEvent');
    Route::post('changePassword', 'UserController@changePassword');
    Route::post('getMyEventList', 'EventController@getMyEventList');      
    Route::post('editMyEvent', 'EventController@editMyEvent');
    Route::post('editMyProfile', 'UserController@editMyProfile');     
    
});

