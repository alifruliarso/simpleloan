<?php

use App\Http\Controllers\LoanController;
use App\Http\Controllers\RepaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/loans', [LoanController::class, 'index'])->middleware('auth:user');
Route::post('/loans', [LoanController::class, 'store'])->middleware('auth:user');
Route::put('/loans/{id}/approve', [LoanController::class, 'approve'])->middleware('auth:admin');
Route::post('/loans/pay', [RepaymentController::class, 'pay'])->middleware('auth:user');
Route::get('/loans/{id}/repayment', [RepaymentController::class, 'index'])->middleware('auth:user');
