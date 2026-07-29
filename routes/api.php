<?php

use App\Http\Controllers\CalculationController;
use Illuminate\Support\Facades\Route;

Route::get('/calculations', [CalculationController::class, 'index']);
Route::post('/calculations', [CalculationController::class, 'store']);
Route::delete('/calculations/{calculation}', [CalculationController::class, 'destroy']);
Route::delete('/calculations', [CalculationController::class, 'clear']);
