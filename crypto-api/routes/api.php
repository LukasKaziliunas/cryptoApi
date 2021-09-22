<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\UserController;
use App\Models\Asset;
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

Route::post('/register', [
    UserController::class, 'register'
]);

Route::post('/login', [
    UserController::class, 'login'
]);

Route::post('/refresh', [
    UserController::class, 'refresh'
]);

Route::middleware(['auth.jwt'])->group(function () {

    Route::get('/user', [
        UserController::class, 'getUser'
    ]);
    
    Route::post('/logout', [
        UserController::class, 'logout'
    ]);

    Route::get('/test' , function(){
        return Asset::where('user_id' ,auth()->user()->id)->get();
    });

    Route::post('/assets', [AssetController::class, 'store']);
    Route::get('/assets', [AssetController::class, 'index']);
    Route::get('/assets/{asset}', [AssetController::class, 'show']);
    Route::delete('/assets/{asset}', [AssetController::class, 'destroy']);
    Route::put('/assets/{asset}', [AssetController::class, 'update']);

    Route::get('/cryptos', [AssetController::class, 'availableCryptos']);

});

Route::get('/test', [AssetController::class, 'test']);


