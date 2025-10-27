<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Frontend\DriverDetailsController;
use App\Http\Controllers\API\Frontend\NotificationController;
use Illuminate\Http\Request;
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
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //Resent OTP API
    Route::get('/resend-otp', [LoginController::class, 'resend_otp']);
    Route::post('/otp-verification', [LoginController::class, 'verify_otp']);

    //Notifications API
    Route::get('/notifications', [NotificationController::class, 'getUserNotifications']);

    //Driver Routes
    Route::group(['prefix' => 'driver'], function () {
        //Driver Vehicle
        Route::get('/vehicle', [DriverDetailsController::class, 'getVehicleDetails']);
        Route::post('/vehicle/update', [DriverDetailsController::class, 'updateVehicleDetails']);

        //Driver License
        Route::get('/license', [DriverDetailsController::class, 'getLicenseDetails']);
        Route::post('/license/update', [DriverDetailsController::class, 'updateLicenseDetails']);

        //Driver Personal Information
        Route::get('/personal-information', [DriverDetailsController::class, 'getPersonalInformation']);
        Route::post('/personal-information/update', [DriverDetailsController::class, 'updatePersonalInformation']);

        //Driver CNIC
        Route::get('/cnic', [DriverDetailsController::class, 'getCNICDetails']);
        Route::post('/cnic/update', [DriverDetailsController::class, 'updateCNICDetails']);
    });

});

// Authentication Routes (Login and Register) for guests
Route::post('/login', [LoginController::class, 'login_attempt']);
Route::post('/register', [RegisterController::class, 'register_attempt']);
// Route::post('/forget-password', [ForgetPasswordController::class, 'forgetPassEmail']);
// Route::post('/reset-password', [ForgetPasswordController::class, 'resetPassword']);
