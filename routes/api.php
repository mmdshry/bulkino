<?php

use App\Http\Controllers\SmsController;
use App\Http\Middleware\CheckBalance;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1')->group(static function () {
    Route::group(['prefix' => 'sms'], static function () {
        Route::group(['middleware' => CheckBalance::class], static function () {
            Route::post('send', [SmsController::class, 'send']);
            Route::post('otp', [SmsController::class, 'otp']);
            Route::post('mkt', [SmsController::class, 'mkt']);
        });
        Route::post('logs', [SmsController::class, 'logs']);

        Route::post('account', [SmsController::class, 'account']);
    });
});