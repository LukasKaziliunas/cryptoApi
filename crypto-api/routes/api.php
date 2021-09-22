<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\UserController;
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

    // Route::post('/assets', [AssetController::class, 'store']);
    // Route::get('/assets', [AssetController::class, 'index']);
    // Route::get('/assets/{asset}', [AssetController::class, 'show']);
    // Route::delete('/assets/{asset}', [AssetController::class, 'destroy']);
    // Route::put('/assets/{asset}', [AssetController::class, 'update']);

});


//testavimui del paprastumo visada bus prisijungta kaip naudotjas id = 1 (reikia seedint db kad atsirastu) 
//jei reikalinga kad butu galima prisijungti patiems, sita koda istrinti ir atkomentuoti routus esancius virsuje
Route::middleware(['dummyUser'])->group(function () {

    Route::post('/assets', [AssetController::class, 'store']);
    Route::get('/assets', [AssetController::class, 'index']);
    Route::get('/assets/{asset}', [AssetController::class, 'show']);
    Route::delete('/assets/{asset}', [AssetController::class, 'destroy']);
    Route::put('/assets/{asset}', [AssetController::class, 'update']);
});

Route::get('/cryptos', [AssetController::class, 'availableCryptos']);

    

