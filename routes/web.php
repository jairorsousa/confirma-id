<?php

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

    Route::get('app', function () {
        return Inertia::render('app/dashboard');
    })->middleware('role:user|admin|super_admin')->name('app.dashboard');

    Route::get('partner', function () {
        return Inertia::render('partner/dashboard');
    })->middleware('role:partner|admin|super_admin')->name('partner.dashboard');

    Route::get('admin', function () {
        return Inertia::render('admin/dashboard');
    })->middleware('role:admin|super_admin')->name('admin.dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
