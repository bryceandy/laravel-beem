<?php

use Bryceandy\Beem\Http\Controllers\TwoWaySmsCallbackController;
use Bryceandy\Beem\Http\Controllers\UssdCallbackController;
use Illuminate\Support\Facades\Route;

Route::post('outbound-sms', TwoWaySmsCallbackController::class)
    ->name('beem.outbound-sms');

Route::post('ussd-callback', UssdCallbackController::class)
    ->name('beem.ussd-callback');
