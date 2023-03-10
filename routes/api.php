<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\AdvertisementController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CardController;
use App\Http\Controllers\API\GeneralizationController;
use App\Http\Controllers\API\WithdrawController;
use App\Http\Controllers\API\WalletController;
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



Route::group([

    'prefix' => 'auth'

], function () {

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
});



Route::group([

    'middleware' => 'auth:api',
    'prefix' => 'auth'

], function () {

    Route::post('logout',  [AuthController::class, 'logout']);
    Route::post('refresh',  [AuthController::class, 'refresh']);
    Route::get('me' ,  [AuthController::class, 'me']);
    Route::post('password/update' ,  [AuthController::class, 'changePassword']);


    Route::post('update' ,  [UserController::class, 'update']);

    Route::get('advertisements',  [AdvertisementController::class, 'index']);

    Route::resource('card', CardController::class);

    Route::resource('withdraw', WithdrawController::class);

    Route::resource('wallet', WalletController::class);

    Route::get('generalizations', [GeneralizationController::class, 'index']);
    Route::get('generalizations/{id}', [GeneralizationController::class, 'show']);

});
