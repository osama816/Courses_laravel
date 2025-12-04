<?php

use App\Models\User;
use App\Models\course;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Instructor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LanguageController;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Payment callback routes
Route::get('/payment/booking/{gateway_type}', [PaymentController::class, 'paymentProcess'])->name('payment.booking');
Route::get('/payment/payment-success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/payment-failed', [PaymentController::class, 'failed'])->name('payment.failed');

// Invoice routes
Route::middleware('auth')->group(function () {
    Route::get('/invoice/{id}/download', [App\Http\Controllers\InvoiceController::class, 'download'])->name('invoice.download');
    Route::get('/invoice/{id}', [App\Http\Controllers\InvoiceController::class, 'show'])->name('invoice.show');
});

// MCP Support Chat
Route::middleware('auth')->post('/mcp/support/chat', [App\Http\Controllers\McpSupportController::class, 'chat'])->name('mcp.support.chat');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/', [PageController::class, 'home'])->name('home');
Route::resource('/courses', CourseController::class);
Route::resource('/bookings', BookingController::class)->except(['create']);
Route::get('/bookings/create/{course}', [BookingController::class, 'create'])
->name('bookings.create');





Route::get('lang/{lang}', [LanguageController::class, 'switch'])->name('lang.switch');

require __DIR__.'/auth.php';
