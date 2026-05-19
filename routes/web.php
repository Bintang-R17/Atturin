<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminMatchController;
use App\Http\Controllers\PlayerJoinController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', function () {
        return redirect()->route('login');
    })->name('login');

    Route::middleware('auth')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::view('events', 'admin.events.index')->name('events.index');
        Route::view('settings', 'admin.settings.index')->name('settings.index');
        Route::view('notifications', 'admin.notifications.index')->name('notifications.index');
        Route::view('faq', 'admin.faq.index')->name('faq.index');

        Route::resource('matches', AdminMatchController::class);
        Route::get('members', [AdminMatchController::class, 'members'])->name('members.index');
        Route::get('finances', [AdminMatchController::class, 'finances'])->name('finances.index');
        Route::get('matches/{match}/live', [AdminMatchController::class, 'live'])->name('matches.live');
        Route::post('matches/{match}/join-visibility', [AdminMatchController::class, 'updateJoinVisibility'])->name('matches.updateJoinVisibility');
        Route::post('matches/{match}/attendance/{player}', [AdminMatchController::class, 'updateAttendance'])->name('matches.updateAttendance');
        Route::post('matches/{match}/status/{player}', [AdminMatchController::class, 'updateStatus'])->name('matches.updateStatus');
        Route::post('matches/{match}/payment/{player}', [AdminMatchController::class, 'updatePayment'])->name('matches.updatePayment');
    });
});

Route::get('/join/{slug}', [PlayerJoinController::class, 'show'])->name('player.join.show');
Route::post('/join/{slug}', [PlayerJoinController::class, 'store'])->name('player.join.store');
Route::get('/join/{slug}/success', [PlayerJoinController::class, 'success'])->name('player.join.success');
Route::post('/join/{slug}/simulate-payment', [PlayerJoinController::class, 'simulateOnlinePayment'])->name('player.join.simulatePayment');
Route::post('/join/{slug}/midtrans/token', [PlayerJoinController::class, 'midtransToken'])->name('player.join.midtrans.token');
Route::post('/join/{slug}/midtrans/finish', [PlayerJoinController::class, 'midtransFinish'])->name('player.join.midtrans.finish');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

require __DIR__ . '/auth.php';
