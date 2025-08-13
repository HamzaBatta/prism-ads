<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



//Route::post('login', [AuthController::class, 'login']);
//Route::post('logout',[AuthController::class,'logout']);
//
//Route::post('ads', [AdController::class, 'store']);
//Route::get('ads',[AdController::class,'index']);
////Route::post('ads/{id}',[AdController::class,'update']);
//Route::delete('ads/{id}',[Adcontroller::class,'destroy']);
Route::post('/ads/{id}/decrement', [AdController::class, 'decrementRemainingUsers']);




