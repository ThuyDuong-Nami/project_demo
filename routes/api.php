<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Admin;
use App\Http\Controllers\API\User;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//})

//Admin
Route::post('admin/login', [Admin\AuthController::class, 'login']);

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('admin/me', [Admin\AuthController::class, 'index']);
    Route::get('admin/logout', [Admin\AuthController::class, 'logout']);

    Route::apiResource('admin', Admin\AdminController::class);
});

//User
Route::post('login', [User\AuthController::class, 'login']);
Route::post('register', [User\AuthController::class, 'register']);
Route::group(['middleware' => 'auth:user'], function () {
    Route::get('/me', [User\AuthController::class, 'index']);
    Route::get('logout', [User\AuthController::class, 'logout']);
});
