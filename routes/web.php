<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Authenticated routes
Route::middleware('auth.jwt')->group(function () {
    Route::get('/', [AdController::class, 'index'])->name('ads.index');
    Route::post('/ads', [AdController::class, 'store'])->name('ads.store');
    Route::delete('/ads/{id}', [AdController::class, 'destroy'])->name('ads.destroy');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
