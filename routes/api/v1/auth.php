<?php

use App\Http\Controllers\Api\V1\Auth\{AuthController, EmailVerifcationController, ForgetPasswordController};
use Illuminate\Support\Facades\Route;

foreach (['agent', 'tourguide'] as $type) {
    Route::group(['prefix' => $type, 'as' => "$type.", 'middleware' => "userType:$type"], function () use ($type) {
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('password/email', [ForgetPasswordController::class, 'forgetPassword'])->name('password.email');
        Route::post('password/check-code', [ForgetPasswordController::class, 'checkCode'])->name('password.code_check');
        Route::post('password/reset', [ForgetPasswordController::class, 'resetPassword'])->name('password.reset');

        Route::middleware("auth:{$type}_api")->group(function () {
            Route::post('check-token', [AuthController::class, 'checkToken'])->name('checkToken');
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
            Route::post('verify/email/generate-otp', [EmailVerifcationController::class, 'generateOtp'])->name('verify.generateOtp');
            Route::post('verify/email/verify-otp', [EmailVerifcationController::class, 'verifyOtp'])->name('verify.verifyOtp');
        });
    });
}
