<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('login', [AuthController::class, 'login']);

Route::post('ads', [AdController::class, 'store']);
Route::post('/ads/{id}/decrement', [AdController::class, 'decrementRemainingUsers']);




