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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('userRegister', 'App\Http\Controllers\UserProfileController@userRegister');
Route::post('/userPost/{userId}', 'App\Http\Controllers\UserPostController@userPost');
Route::post('/userLikes/{userId}/{postId}', 'App\Http\Controllers\UserLikesController@likesImage');
Route::post('/userComments/{userId}/{postId}', 'App\Http\Controllers\UserCommentsController@userComments');
Route::put('/updateComments/{userId}/{commentsDetailId}', 'App\Http\Controllers\UserCommentsController@updateComments');
Route::delete('/deleteComments/{userId}/{commentsDetailId}', 'App\Http\Controllers\UserCommentsController@deleteComments');
Route::post('search', 'App\Http\Controllers\UserProfileController@searchUser');
Route::post('/follow/{userProfileId}/{userId}', 'App\Http\Controllers\UserFollowingController@follow');
Route::post('/unfollow/{userProfileId}/{userId}', 'App\Http\Controllers\UserFollowingController@unfollow');
Route::get('/viewFollowers/{userId}', 'App\Http\Controllers\UserFollowingController@followers');
Route::get('/viewFollowings/{userId}', 'App\Http\Controllers\UserFollowingController@followings');
Route::get('/viewPost/{userId}/{postId}', 'App\Http\Controllers\UserPostController@viewPost');
