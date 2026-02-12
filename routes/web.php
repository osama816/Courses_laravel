<?php
use Rap2hpoutre\LaravelLogViewer\LogViewerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    PageController,
    CourseController,
    BookingController,
    PaymentController,
    ProfileController,
    LanguageController,
    InvoiceController,
    McpSupportController
};



//logs
Route::get('/logs', [LogViewerController::class, 'index']);

/**
 * Home Page
 */
Route::get('/', [CourseController::class, 'index'])->name('home');

/**
 * Language Switcher
 */
Route::get('lang/{lang}', [LanguageController::class, 'switch'])->name('lang.switch');

/**
 * Courses - Public Browse & View
 */
Route::resource('courses', CourseController::class)->only(['index', 'show'])->where(['course' => '[0-9]+']);

// ============================================================================
// Authentication Required Routes
// ============================================================================

Route::middleware(['auth', 'verified'])->group(function () {

    // ------------------------------------------------------------------------
    // Dashboard
    // ------------------------------------------------------------------------
    Route::redirect('/dashboard', '/')->name('dashboard');
    // ------------------------------------------------------------------------
    // courses
    // ------------------------------------------------------------------------
    Route::middleware(['auth', 'permission:create courses'])->group(function () {
        Route::get('courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('courses', [CourseController::class, 'store'])->name('courses.store');
    });

    Route::middleware(['auth', 'permission:edit courses'])->group(function () {
        Route::get('courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    });

    Route::middleware(['auth', 'permission:delete courses'])->group(function () {
        Route::delete('courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    });


    // ------------------------------------------------------------------------
    // Booking Management
    // ------------------------------------------------------------------------
    Route::prefix('bookings')->middleware(['auth'])->group(function () {

        Route::get('/', [BookingController::class, 'index'])
            ->middleware('permission:view bookings')
            ->name('bookings.index');

        Route::get('/{booking}', [BookingController::class, 'show'])
            ->middleware('permission:view bookings')
            ->name('bookings.show');

        Route::get('/create/{course}', [BookingController::class, 'create'])
            ->middleware('permission:create bookings')
            ->name('bookings.create');

        Route::post('/', [BookingController::class, 'store'])
            ->middleware('permission:create bookings')
            ->name('bookings.store');

        Route::put('/{booking}', [BookingController::class, 'update'])
            ->middleware('permission:update booking status')
            ->name('bookings.update');

        Route::delete('/{booking}', [BookingController::class, 'destroy'])
            ->middleware('permission:cancel bookings')
            ->name('bookings.destroy');
    });


    // ------------------------------------------------------------------------
    // Payment Processing
    // ------------------------------------------------------------------------
    Route::prefix('payment')->middleware(['auth'])->group(function () {
        Route::get('/booking/{gateway_type}', [PaymentController::class, 'paymentProcess'])
            ->middleware('permission:view payments')
            ->name('payment.booking');

        Route::any('/callback', [PaymentController::class, 'callBack'])
            ->name('payment.callback');

        Route::get('/success', [PaymentController::class, 'success'])
            ->name('payment.success');

        Route::get('/failed', [PaymentController::class, 'failed'])
            ->name('payment.failed');
    });


    // ------------------------------------------------------------------------
    // Invoice Management
    // ------------------------------------------------------------------------
    Route::prefix('invoice')->name('invoice.')->group(function () {
        // View invoice
        Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');

        // Download invoice PDF
        Route::get('/{id}/download', [InvoiceController::class, 'download'])->name('download');
    });

    // ------------------------------------------------------------------------
    // AI Support Chat (MCP Server)
    // ------------------------------------------------------------------------
    Route::prefix('mcp')->name('mcp.')->group(function () {
        // Send chat message to AI support
        Route::post('/support/chat', [McpSupportController::class, 'chat'])
            ->name('support.chat');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================================================================
// Additional Authentication Routes
// ============================================================================

/**
 * Include Laravel Breeze authentication routes
 * (login, register, password reset, etc.)
 */
require __DIR__ . '/auth.php';

