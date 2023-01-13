<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MovieController;
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
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('login'     , 'login');
    Route::post('register'  , 'register');
    Route::post('logout'    , 'logout');
    Route::post('refresh'   , 'refresh');
});
Route::apiResource('categories' , CategoryController::class);

Route::prefix('movies')->controller(MovieController::class)->group(function () {
Route::apiResource('/'     , MovieController::class);
Route::post('search',[MovieController::class,'search']);
});
