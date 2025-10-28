<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CalendarEventController;

// Home
Route::get('/', function () {
    return view('home');
});

// Registro
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Reset de contraseña
Route::get('/password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

// Rutas protegidas
Route::middleware(['auth'])->group(function() {
    Route::get('/calendars/create', [CalendarController::class, 'create'])->name('calendars.create');
    Route::post('/calendars', [CalendarController::class, 'store'])->name('calendars.store');
    Route::get('/calendars/{calendar}', [CalendarController::class, 'show'])->name('calendars.show');

    Route::get('/calendars/{calendar}/events', [CalendarEventController::class, 'index'])->name('calendars.events.index');
    Route::post('/calendars/{calendar}/events', [CalendarEventController::class, 'store'])->name('calendars.events.store');
    Route::patch('/events/{event}', [CalendarEventController::class, 'update'])->name('calendars.events.update');
    Route::delete('/events/{event}', [CalendarEventController::class, 'destroy'])->name('calendars.events.destroy');
});
