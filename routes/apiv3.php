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

//Route::post('userList', 'UserController@getUserList');

Route::post('register', 'UserController@register');
Route::post('eventList', 'EventController@getEventList'); 
Route::group(['middleware' => ['jwt.verify']], function() {
    Route::post('addEvent', 'EventController@addEvent');    
});


