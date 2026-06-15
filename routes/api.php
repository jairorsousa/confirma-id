<?php

use App\Http\Controllers\Api\PartnerIdentityQueryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:partner-api'])
    ->post('partner/identity-query', PartnerIdentityQueryController::class)
    ->name('api.partner.identity-query');
