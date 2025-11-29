<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::match(['GET','POST'],'/payment/process', [PaymentController::class, 'paymentProcess'])->name('payment.process')->middleware('throttle:limit3');
Route::match(['GET','POST'],'/payment/callback', [PaymentController::class, 'callBack'])->middleware('throttle:limit3');
