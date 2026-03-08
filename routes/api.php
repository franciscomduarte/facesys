<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::post('webhooks/payment', [WebhookController::class, 'handle'])->name('webhooks.payment');
