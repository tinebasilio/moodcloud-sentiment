<?php

use App\Http\Controllers\SentimentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

// Main route (Welcome page) for unauthenticated users
Route::middleware('guest')->get('/', function () {
    return view('welcome');  // This will display the welcome page for unauthenticated users
})->name('welcome');

Route::middleware('auth')->get('/home', [SentimentController::class, 'index'])->name('home');

// Sentiment analysis routes, history, reports, theme/settings - Only for authenticated users
Route::middleware('auth')->group(function () {

    // Sentiment analysis routes (only for authenticated users)
    Route::get('/analyze', [SentimentController::class, 'create'])->name('analyze');
    Route::post('/analyze', [SentimentController::class, 'store'])->name('store');
    
    // History and reports
    Route::get('/history', [SentimentController::class, 'history'])->name('history');
    Route::get('/report/{id}', [SentimentController::class, 'generateReport'])->name('report.generate');
    Route::get('/report/{id}/download', [SentimentController::class, 'downloadReport'])->name('report.download');
    Route::delete('/delete/{id}', [SentimentController::class, 'softDelete'])->name('softDelete');
    Route::post('/export', [SentimentController::class, 'export'])->name('export');

    // Theme and settings
    Route::get('/settings', [SentimentController::class, 'settings'])->name('settings');
    Route::post('/settings', [SentimentController::class, 'updateSettings'])->name('settings.update');
    Route::post('/set-theme', [ThemeController::class, 'setTheme'])->name('theme.set');

    // Profile routes
    Route::middleware('verified')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__.'/auth.php';
