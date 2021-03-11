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

Route::post('userList', 'UserController@getUserList');
Route::post('eventList', 'EventController@getEventList'); 

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', 'UserController@getAuthenticatedUser');        
    Route::post('addEvent', 'EventController@addEvent');
    Route::post('changePassword', 'UserController@changePassword');
    Route::post('getMyEventList', 'EventController@getMyEventList');  
    Route::post('addUpdateRating', 'EventController@addUpdateRating');  
    
});

// Route::group(['prefix' => 'api/v2'], function () {
//     Route::post('eventList', 'v2\EventController@getEventList');    
// });