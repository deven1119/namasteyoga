<?php

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

Route::get('/logout', function () {
    Auth::logout();
    return view('login');
});


Route::get('/activate_account', 'UserController@activate_account');

Route::get('/users/sendmail', 'UserController@pushNotification');
Route::get('/deletetest', 'UserController@deletetest');

Route::group(['middleware' => 'App\Http\Middleware\AuthMiddleware'], function () {

    
    Route::get('/users', 'UserController@index')->middleware(['App\Http\Middleware\CheckRole']);
    Route::post('/userIndexAjax', 'UserController@userIndexAjax');


    Route::get('/users/center', 'UserController@center')->middleware(['App\Http\Middleware\CheckRole']);
    Route::post('/userCenterIndexAjax', 'UserController@userCenterIndexAjax');

    Route::get('/users/pendings', 'UserController@pendings');
    Route::get('/users/rejected', 'UserController@rejected');
    Route::post('/userPendingIndexAjax', 'UserController@userPendingIndexAjax');
    Route::post('/userRejectedIndexAjax', 'UserController@userRejectedIndexAjax');




    Route::get('/home', 'HomeController@index')->name('home');
    /* users routing */
    //Route::get('/users', 'UserController@index');
    Route::get('/users/edit/{id}', 'UserController@edit');
    Route::get('/users/add', 'UserController@add');
    Route::post('/users/add', 'UserController@add');
    Route::get('/users/moderator_list', 'UserController@moderator_list');
    Route::post('/users/moderatorIndexAjax', 'UserController@moderatorIndexAjax');

    Route::get('/users/changepass', 'UserController@changepass');
    Route::post('/users/changepass', 'UserController@changepass');
    Route::post('/users/update/{id}', 'UserController@update');
    Route::post('/users/create', 'UserController@create');
    Route::get('/users/destroy/{id}', 'UserController@destroy');
    Route::post('/users/changestatus', 'UserController@changestatus');
    Route::post('/users/changemodratorstatus', 'UserController@changemodratorstatus');
    Route::post('/users/changeycbstatus', 'UserController@changeycbstatus');
    Route::post('/users/resetModeratorPassword', 'UserController@resetModeratorPassword');





    Route::get('/events', 'EventController@index')->middleware(['App\Http\Middleware\CheckRole']);
	Route::get('/events/pending', 'EventController@pendingEvents')->middleware(['App\Http\Middleware\CheckRole']);
    Route::post('/events/eventIndexAjax', 'EventController@eventIndexAjax');
    Route::post('/events/pendingEventIndexAjax', 'EventController@pendingEventIndexAjax');

    Route::get('/audittrails', 'AuditController@index')->middleware(['App\Http\Middleware\CheckRole']);
    Route::post('/auditIndexAjax', 'AuditController@auditIndexAjax');
    Route::post('/events/changestatus', 'EventController@changestatus');

	Route::get('/polls', 'PollsController@index')->middleware(['App\Http\Middleware\CheckRole']);
	
	
	
	//Polls Module
	Route::resource('/polls', 'PollsController');
    Route::post('/polls/pollsIndexAjax', 'PollsController@pollsIndexAjax');
	Route::get('/polls/create', 'PollsController@create');
	Route::get('/polls/edit/{id}', 'PollsController@show');
	Route::get('/polls/view/{id}', 'PollsController@view');
	Route::post('/polls/update/{id}', 'PollsController@update');
    Route::post('/polls/changestatus', 'PollsController@changestatus');
    Route::post('/polls/destroy', 'PollsController@destroy');
    Route::get('/polls/viewresult/{id}', 'PollsController@viewResult');
    Route::get('/notifyUsersPollAboutToEnd', 'PollsController@PollsAboutToExpire');
	
	
	//Quiz Module
	Route::resource('/quiz', 'QuizController');
	Route::post('/quiz/quizIndexAjax', 'QuizController@quizIndexAjax');
	Route::get('/quiz/create', 'QuizController@create');
	Route::get('/quiz/add/{quiz_id}', 'QuizController@AddQuestions');
	Route::post('/quiz/storeQuestions', 'QuizController@storeQuestions');
	Route::get('/quiz/edit/{id}', 'QuizController@show');
	Route::get('/quiz/view/{id}', 'QuizController@view');
	Route::post('/quiz/update/{id}', 'QuizController@update');
    Route::post('/quiz/changestatus', 'QuizController@changestatus');
    Route::post('/quiz/destroy', 'QuizController@destroy');
    Route::get('/quiz/viewresult/{id}', 'QuizController@viewResult');
	
	/* For general notifications */
    Route::get('/generalnotifications', 'Notifications@listGeneralNotifications');
	Route::post('/notificationIndexAjax', 'Notifications@notificationIndexAjax');
	
    Route::any('/sendgeneralnotification', 'Notifications@sendGeneralNotification');//->middleware(['App\Http\Middleware\CheckRole']);
	/*---------------------------------------- Aasana Category Module-------------------------------------------------------- */
	Route::get('/aasana/addcategory','AasanaCategory@AddCategory');
	Route::post('/aasana/savecategoy','AasanaCategory@SaveCategory');
    Route::get('/aasana/listcategory','AasanaCategory@ListCategory');
    Route::post('/aasana/categoryIndexAjax', 'AasanaCategory@CategoryIndexAjax');
    Route::post('/aasana/changestatus', 'AasanaCategory@ChangeCategoryStatus');
    Route::get('/aasana/deletecategory/{id}', 'AasanaCategory@DeleteCategory');
    Route::get('/aasana/viewcategory/{id}', 'AasanaCategory@ViewCategory');
    Route::get('/aasana/editcategory/{id}', 'AasanaCategory@EditCategory');
    Route::post('/aasana/updatecategory/{id}', 'AasanaCategory@UpdateCategory');
	 //----------------------------------Aasana Sub Category Routes List---------------------------------------------------------------//
    Route::get('/aasana/addsubcategory','AasanaSubCategoryController@AddSubCategory');
    Route::post('/aasana/savesubcategoy','AasanaSubCategoryController@SaveSubCategory');
    Route::get('/aasana/listsubcategory','AasanaSubCategoryController@ListSubCategory');
    Route::post('/aasana/subcategoryIndexAjax', 'AasanaSubCategoryController@SubCategoryIndexAjax');
    Route::post('/aasana/changestatussubcategory', 'AasanaSubCategoryController@ChangeSubCategoryStatus');
    Route::get('/aasana/deletesubcategory/{id}', 'AasanaSubCategoryController@DeleteSubCategory');
    Route::get('/aasana/viewsubcategory/{id}', 'AasanaSubCategoryController@ViewSubCategory');
    Route::get('/aasana/editsubcategory/{id}', 'AasanaSubCategoryController@EditSubCategory');
    Route::post('/aasana/updatesubcategory/{id}', 'AasanaSubCategoryController@UpdateSubCategory');
	
  //-----------------------Aasana  Routes List---------------------------------------------------------------//
    
     Route::get('/aasana/addaasana','Aasana@AddAasana');
     Route::post('/aasana/getsubcategorybycategory','Aasana@getSubCategory');
     Route::post('/aasana/saveaasana','Aasana@SaveAasana');
     Route::get('/aasana/listsaasana','Aasana@ListAasana');
     Route::post('/aasana/aasanaIndexAjax', 'Aasana@AasanaIndexAjax');
     Route::post('/aasana/changestatusaasana', 'Aasana@ChangeAasanaStatus');
     Route::get('/aasana/deleteaasana/{id}', 'Aasana@DeleteAasana');
     Route::get('/aasana/viewaasana/{id}', 'Aasana@ViewAasana');
     Route::get('/aasana/editaasana/{id}', 'Aasana@EditAasana');
     Route::post('/aasana/geteditsubcategorybycategory','Aasana@getEditSubCategory');
     Route::post('/aasana/updateaasana/{id}', 'Aasana@UpdateAasana');
	 
	 //-----------------------------Social Media Routes List---------------------------------------------------------------//
    
      Route::get('/socialmedia/addsocialmedia','SocialMediaController@AddSocialMedia');
      //Route::post('/aasana/getsubcategorybycategory','Aasana@getSubCategory');
      Route::post('/socialmedia/savesocialmedia','SocialMediaController@SaveSocialMedia');
      Route::get('/socialmedia/listssocialmedia','SocialMediaController@ListSocialMedia');
      Route::post('/socialmedia/socialmediaIndexAjax', 'SocialMediaController@SocialMediaIndexAjax');
      Route::post('/socialmedia/changestatussocialmedia', 'SocialMediaController@ChangeSocialMediaStatus');
      Route::get('/socialmedia/deletesocialmedia/{id}', 'SocialMediaController@DeleteSocialMedia');
      Route::get('/socialmedia/viewsocialmedia/{id}', 'SocialMediaController@ViewSocialMedia');
      Route::get('/socialmedia/editsocialmedia/{id}', 'SocialMediaController@EditSocialMedia');
     // Route::post('/aasana/geteditsubcategorybycategory','Aasana@getEditSubCategory');
      Route::post('/socialmedia/updatesocialmedia/{id}', 'SocialMediaController@UpdateSocialMedia');
    

});


Route::any('/forgotpassword', 'UserController@forgotpassword');
Route::any('/changepassword_second', 'UserController@changepassword_second');



Route::get('/', 'PagesController@index');

// Route::group(['middleware' => 'App\Http\Middleware\AdminMiddleware'], function(){
//     Route::match(['get', 'post'], '/adminOnlyPage/', 'HomeController@admin');
// });

Auth::routes();

/* error routing */
Route::get('/error',function(){
   abort('custom');
});
