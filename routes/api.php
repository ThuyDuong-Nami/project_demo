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

    Route::apiResources([
        'admin' => Admin\AdminController::class,
        'user' => Admin\UserController::class,
        'category' => Admin\CategoryController::class,
        'product' => Admin\ProductController::class,
    ]);

    Route::post('admin/search', [Admin\AdminController::class, 'search']);
    Route::post('user/search', [Admin\UserController::class, 'search']);
    Route::post('category/search', [Admin\CategoryController::class, 'search']);
    Route::post('product/search', [Admin\ProductController::class, 'search']);
    Route::post('bill/search', [Admin\BillController::class, 'search']);

    Route::post('file/import', [Admin\ProductController::class, 'import']);

    Route::apiResource('bill', Admin\BillController::class)->except('store', 'destroy');
    Route::get('export', [Admin\BillController::class, 'export']);
});

//User
Route::prefix('public')->group(function (){
    Route::post('login', [User\AuthController::class, 'login']);
    Route::post('register', [User\AuthController::class, 'register']);
    Route::group(['middleware' => 'auth:user'], function () {
        Route::get('/me', [User\AuthController::class, 'index']);
        Route::get('logout', [User\AuthController::class, 'logout']);

        Route::get('profile', [User\ProfileController::class, 'index']);
        Route::patch('profile', [User\ProfileController::class, 'update']);
        Route::patch('profile/changepass', [User\ProfileController::class, 'changePass']);
        Route::patch('profile/changeaddress', [User\ProfileController::class, 'changeAddress']);

        Route::post('checkout', [User\BillController::class, 'createBill']);
    });

    Route::get('/', [User\HomeController::class, 'index']);
    Route::get('sidebar', [User\HomeController::class, 'sidebar']);
    Route::get('category/{category}', [User\HomeController::class, 'productsCategory']);
    Route::get('product/{product}', [User\HomeController::class, 'productDetail']);
});
//Route::get('export', [Admin\BillController::class, 'export']);
