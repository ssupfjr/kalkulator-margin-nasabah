<?php

use App\Http\Controllers\RiskCalculatorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('calculator.index');
});

Route::get('/calculator', [RiskCalculatorController::class, 'index'])
    ->name('calculator.index');

Route::post('/calculator/calculate', [RiskCalculatorController::class, 'calculate'])
    ->name('calculator.calculate');