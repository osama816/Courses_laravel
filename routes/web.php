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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// Route::get('cerate_course', function(){
//     Category::create([
//         'name'=> 'devolpar',
//         'slug'=> 'dev',
//     ]);
//     Instructor::create([
//         'user_id'=> '1',
//         'bio'=> 'bio',
//         'avatar_url'=> 'contact-bg.jpg'
//     ]);

//     course::create([
//         'title' => 'PHP',
//         'description' => 'PHP',
//         'category_id' => 1,
//         'instructor_id' => '1',
//         'price' => 1000,
//         'level' => 'Beginner',
//         'image_url' => 'contact-bg.jpg',
//         'total_seats' => 10,
//         'available_seats' => 10,
//         'rating' => 0,
//         'duration' => 0
//     ]);    Booking::create([
//         'user_id'=> '1',
//         "course_id" => '3',
//         "seats" => 'pending',
//         'payment_method'=>'cash'
//     ]);
// });

Route::get('/', [PageController::class, 'home'])->name('home');
Route::resource('/courses', CourseController::class);
Route::resource('/bookings', BookingController::class)->except(['create']);
Route::get('/bookings/create/{course}', [BookingController::class, 'create'])
->name('bookings.create');





Route::get('lang/{lang}', [LanguageController::class, 'switch'])->name('lang.switch');

require __DIR__.'/auth.php';
