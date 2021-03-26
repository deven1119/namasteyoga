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
    Route::post('/events/eventIndexAjax', 'EventController@eventIndexAjax');

    Route::get('/audittrails', 'AuditController@index')->middleware(['App\Http\Middleware\CheckRole']);
    Route::post('/auditIndexAjax', 'AuditController@auditIndexAjax');
    Route::post('/events/changestatus', 'EventController@changestatus');
    Route::get('addcategory','AasanaCategory@AddCategory');
    Route::post('savecategoy','AasanaCategory@SaveCategory');
    
   


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

//---------------------------------------------------------Abhilasha Aasana Category Routes---------------------------------------------------------//


