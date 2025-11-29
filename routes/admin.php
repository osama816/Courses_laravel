<?php
use Illuminate\Support\Facades\Route;
Route::prefix('admin')->group(function () {
    Route::get('/dashobard', function () {
        return'Admin Dashboard';
    });
 Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
});
