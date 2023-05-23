<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RetrieveDataController;
use App\Http\Controllers\SaveDataController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['auth_check', 'prevent_back_history']], function () {
    Route::get('/login', [AuthController::class, 'LoginPage'])->name('login');
    Route::post('/validate', [AuthController::class, 'AuthValidation'])->name('validate');
});

Route::group(['middleware' => ['auth', 'prevent_back_history']], function () {
    Route::get('/', [RetrieveDataController::class, 'Dashboard'])->name('dashboard');

    //manage card
    Route::get('/managecard', [RetrieveDataController::class, 'ManageCard'])->name('managecard');
    Route::post('/registercard', [SaveDataController::class, 'RegisterCard'])->name('registercard');
    Route::post('/uploadcsv', [SaveDataController::class, 'UploadCsv'])->name('uploadcsv');
    Route::get('/viewdepositfund', [RetrieveDataController::class, 'ViewDepositFund'])->name('viewdepositfund');
    Route::post('/depositfund', [SaveDataController::class, 'DepositFund'])->name('depositfund');
    Route::get('/downloadreceipt/{id}', [SaveDataController::class, 'DownloadReceipt'])->name('download');
    Route::post('/report', [SaveDataController::class, 'generateReport'])->name('report');
    Route::post('/payment-report', [SaveDataController::class, 'paymentReport'])->name('payment-report');
    Route::post('/blockcard', [SaveDataController::class, 'blockCard'])->name('blockcard');
    Route::post('/un-blockcard', [SaveDataController::class, 'unBlockCard'])->name('un-blockcard');
    Route::post('/deletecard', [SaveDataController::class, 'deleteCard'])->name('deletecard');
    Route::post('/deleteresident', [SaveDataController::class, 'deleteResident'])->name('deleteresident');
    //Route::get('delete-card/{id}', [SaveDataController::class, 'deleteCard'])->name('delete-card');


    //manage resident
    Route::get('/manageuser', [RetrieveDataController::class, 'ManageUser'])->name('manageuser');
    Route::post('/registerresident', [SaveDataController::class, 'RegisterResident'])->name('registerresident');

    Route::get('/generatereport', [RetrieveDataController::class, 'GenerateReport'])->name('generatereport');



    Route::get('/auth/logout', [AuthController::class, 'Logout'])->name('auth.logout');
});


//call event
Route::get('/seed-event', [SaveDataController::class, 'seedEvent']);
Route::get('/optimize-event', [SaveDataController::class, 'optimizeEvent']);
