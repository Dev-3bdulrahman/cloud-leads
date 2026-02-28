<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\UserController;

// ===== Auth Routes (guests only) =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class , 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class , 'login'])->name('login.post');
});

// Logout (auth required)
Route::post('/logout', [AuthController::class , 'logout'])->name('logout')->middleware('auth');

// ===== Protected Routes =====
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('leads.index'));

    Route::prefix('leads')->name('leads.')->group(function () {
            Route::get('/', [LeadController::class , 'index'])->name('index');
            Route::get('/create', [LeadController::class , 'create'])->name('create');
            Route::post('/', [LeadController::class , 'store'])->name('store');
            Route::post('/{lead}/assign', [LeadController::class , 'assign'])->name('assign');
        }
        );

        Route::resource('users', UserController::class);    });
