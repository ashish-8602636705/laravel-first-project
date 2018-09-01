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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getImages','ApiController@index')->name('getImages');
Route::any('/getImageById','ApiController@getImagesByCategoryId')->name('getImageById');

Route::post('/signup/','API\UserController@signup');
Route::post('/signin/','API\UserController@signin');
Route::get('/getbrand/','API\UserController@getAllBrand');
Route::post('/getproduct/','API\UserController@getAllProduct');
Route::get('/getillness/','API\UserController@diagnosed_illness');
Route::post('/getchart/','API\UserController@getChart');
Route::post('/updateprofile/','API\UserController@updateUserProfile');
Route::post('/forgetpassword/','API\UserController@forget_password');
Route::post('/version/','API\UserController@validate_version');
Route::post('/selectdTest/','API\UserController@getSelectedTest');
Route::post('/save_result/','API\UserController@saveAndUpdateUserTest');
Route::post('/show_result/','API\UserController@showResult');
Route::post('/getProfile/','API\UserController@getProfile');
Route::post('/change_email/','API\UserController@changeEmail');
Route::post('/change_password/','API\UserController@changePassword');
Route::post('/save_illness/','API\UserController@saveIllness');
Route::post('/get_all_result/','API\UserController@getAllTestByUser');
Route::post('/Send_notifiction/','API\UserController@Send_notifiction');
Route::post('/demo/','API\UserController@demo');
Route::post('/editChart/','API\UserController@getEditedChart');




//Friend
Route::post('/getAllusers/','API\FriendsController@getAlluser');
Route::post('/send_request/','API\FriendsController@RequestSend');
Route::post('/accept_request/','API\FriendsController@accept_request');
Route::post('/getAllfriends/','API\FriendsController@getAllfriends');
Route::post('/search/','API\FriendsController@searchUser');
Route::post('/unfriend/','API\FriendsController@unfriend');
Route::post('/cancel_request/','API\FriendsController@cancelRequest');
Route::post('/invite_friend/','API\FriendsController@inviteFriend');
Route::post('/get_request/','API\FriendsController@getAllRequest');
Route::post('/share_result_with_friend/','API\FriendsController@share_result_with_friend');
Route::post('/get_friend_report/','API\FriendsController@getFriendsReport');
Route::post('/getSharedresult/','API\FriendsController@getShareResult');
Route::post('/share_all_result/','API\FriendsController@shareAllResult');
Route::post('/getAllSharedResult/','API\FriendsController@getAllSharedResult');
Route::post('/remove_share/','API\FriendsController@removeShare');
Route::post('/shareAllResultByStatus/','API\FriendsController@shareAllResultByStatus');


Route::get('/view_t_n_c/','API\TermsAndConditionsController@index');
Route::get('/reset_password/{email}','API\TermsAndConditionsController@reset_password');
Route::post('/password_reset','API\TermsAndConditionsController@password_reset_save');









?>