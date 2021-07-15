<?php

use Bryceandy\Beem\Http\Controllers\UssdCallbackController;
use Illuminate\Support\Facades\Route;

Route::post('beem-ussd-callback', UssdCallbackController::class)
    ->name('beem.ussd.callback');