<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\UserController;
use App\Http\Controllers\Api\Post\PostController;
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

Route::group(
    ['prefix' => 'users/v1'],
    function ($router) {
        Route::post('/register', [UserController::class, 'store']);
        Route::post('/login', [AuthController::class, 'login']);
    }
);

Route::group(['prefix' => 'posts/v1'], function ($router) {
    Route::get('index', [PostController::class, 'index']);
    Route::get('show/{id}', [PostController::class, 'show']);
    Route::get('profile_posts_by_profileId/{id}', [PostController::class, 'index_by_user_id']);
    Route::post('store', [PostController::class, 'store']);
    Route::put('update/{id}', [PostController::class, 'update']);
    Route::get('destroy/{id}', [PostController::class, 'destroy']);
});
