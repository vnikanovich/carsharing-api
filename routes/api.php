<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\CarController;
use App\Http\Controllers\Api\UserController;
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

Route::apiResource('users', UserController::class);
Route::apiResource('cars', CarController::class);

Route::get('list', [ApiController::class, 'list']);

Route::post('bind-car', [ApiController::class, 'bindCar']);
Route::post('unbind-car', [ApiController::class, 'unbindCar']);
