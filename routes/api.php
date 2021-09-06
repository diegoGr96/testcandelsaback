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

Route::post('register', 'App\Http\Controllers\UserController@register');
Route::post('login', 'App\Http\Controllers\UserController@authenticate');


Route::group(['middleware' => ['jwt.verify']], function () {
    Route::post('user', 'App\Http\Controllers\UserController@getAuthenticatedUser');

    Route::prefix('posts')->group(function () {
        Route::post('/', 'App\Http\Controllers\PostsController@store');
        Route::delete('/{id}', 'App\Http\Controllers\PostsController@destroy');
        Route::put('/{id}', 'App\Http\Controllers\PostsController@update');
    });

    Route::prefix('likes')->group(function () {
        Route::post('/', 'App\Http\Controllers\LikesController@store');
        Route::delete('/{id}', 'App\Http\Controllers\LikesController@destroy');
    });
});

Route::prefix('user')->group(function () {
    Route::get('/{id}/posts', 'App\Http\Controllers\UserController@showPosts');
});

Route::prefix('posts')->group(function () {
    Route::get('/', 'App\Http\Controllers\PostsController@index');
    Route::get('/{id}', 'App\Http\Controllers\PostsController@show');
    Route::get('/{id}/likes', 'App\Http\Controllers\PostsController@showLikesByPostId');
});

Route::prefix('likes')->group(function () {
    Route::get('/', 'App\Http\Controllers\LikesController@index');
    Route::get('/{id}', 'App\Http\Controllers\LikesController@show');
});
