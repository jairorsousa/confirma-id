<?php

use App\Http\Controllers\App\UserVerificationController;
use App\Support\RoleRedirector;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return redirect(RoleRedirector::pathFor(request()->user()));
    })->name('dashboard');

    Route::get('app', [UserVerificationController::class, 'show'])
        ->middleware('role:user|admin|super_admin')
        ->name('app.dashboard');

    Route::post('app/verification', [UserVerificationController::class, 'store'])
        ->middleware('role:user')
        ->name('app.verification.store');

    Route::get('partner', function () {
        return Inertia::render('partner/dashboard');
    })->middleware('role:partner|admin|super_admin')->name('partner.dashboard');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
