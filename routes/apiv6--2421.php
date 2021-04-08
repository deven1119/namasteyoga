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
Route::get('getPoll', 'PollController@getPoll');
	Route::post('validatePoll', 'PollController@validatePoll');
	Route::post('submitPoll', 'PollController@submitPoll');

Route::post('validatePoll', 'PollController@validatePoll');
Route::post('submitPoll', 'PollController@submitPoll');

Route::group(['middleware' => ['jwt.verify']], function() {
    
	
});

?>