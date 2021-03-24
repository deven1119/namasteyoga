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
Route::post('GetSubCategoryList', 'AasanController@getSubCategoryList');
Route::post('GetAasanaList', 'AasanController@getAasanaList');


Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::post('apilogout', 'UserController@apilogout');
    Route::post('addEvent', 'EventController@addEvent');
    Route::post('changePassword', 'UserController@changePassword');
    Route::post('getMyEventList', 'EventController@getMyEventList');
    Route::post('editMyEvent', 'EventController@editMyEvent');
    Route::post('editMyProfile', 'UserController@editMyProfile');
    Route::post('suspendAccount', 'UserController@suspendAccount');
    Route::post('GetFeedbackQuestionList','FeedbackController@getFeedbackQuestionList');
	Route::post('SubmitFeedback','FeedbackController@submitFeedback');

});



?>