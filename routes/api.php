<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CategoryController;
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
Route::group([
    'middleware' => 'api'
], function ($router) {
    
    /**
     * Authentication Module
     */
    Route::group(['prefix' => 'auth'], function() {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });
    

    /**
     * Products Module
     */
    Route::resource('products', ProductsController::class);
    Route::get('products/view/all', [ProductsController::class, 'indexAll']);
    Route::get('products/view/search', [ProductsController::class, 'search']);

    /**
     * Business
     */
    Route::controller(BusinessController::class)->group(function () {
        Route::get('business', 'fetchAllData');
        Route::get('business/{slug}', 'showslug');
        Route::get('businesses/search', 'index');
        Route::post('business', 'store');
        Route::put('business/{id}', 'update');
        Route::delete('business/{id}', 'delete');
        
    });
    
    Route::get('category', [CategoryController::class, 'index']);

});

