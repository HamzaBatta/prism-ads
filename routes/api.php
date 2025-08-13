<?php

use App\Http\Controllers\AdController;

use Illuminate\Support\Facades\Route;




Route::post('/ads/{id}/decrement', [AdController::class, 'decrementRemainingUsers']);




