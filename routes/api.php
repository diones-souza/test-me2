<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScaleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', AuthController::class . '@authenticate');
});

Route::group(['prefix' => 'users', 'middleware' => 'jwt'], function () {
    Route::get('/', UserController::class . '@getItems');
    Route::post('/', UserController::class . '@create');
    Route::put('/{id}', UserController::class . '@update');
    Route::delete('/{id}', UserController::class . '@delete');
});

Route::group(['prefix' => 'scales', 'middleware' => 'jwt'], function () {
    Route::get('/', ScaleController::class . '@getItems');
    Route::post('/', ScaleController::class . '@create');
    Route::put('/{id}', ScaleController::class . '@update');
    Route::delete('/{id}', ScaleController::class . '@delete');
});

Route::group(['prefix' => 'points', 'middleware' => 'jwt'], function () {
    Route::get('/', PointController::class . '@getItems');
    Route::post('/', PointController::class . '@create');
    Route::put('/{id}', PointController::class . '@update');
    Route::delete('/{id}', PointController::class . '@delete');
});
