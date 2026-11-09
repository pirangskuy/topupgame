<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MidtransWebhookController;

Route::post('/midtrans/notify', [MidtransWebhookController::class, 'handle'])
    ->name('midtrans.notify');
