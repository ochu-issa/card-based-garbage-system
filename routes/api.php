<?php

use App\Http\Controllers\AppServiceController;
use App\Http\Controllers\AuthAppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SaveDataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//scaning card route for hardware
Route::get('scan-card/{card_number}', [SaveDataController::class, 'ScanCard']);

//public routing
Route::post('/registerUser', [AuthAppController::class, 'RegisterUser']);
Route::post('/loginUser', [AuthAppController::class, 'LoginUser']);

//protected routing
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logoutUser', [AuthAppController::class, 'logoutUser']);

    Route::post('/link-card', [AppServiceController::class, 'linkCard']);
    Route::post('/request-service', [AppServiceController::class, 'collectionRequest']);


});
