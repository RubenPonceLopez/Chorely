<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\FlatMemberController;
use App\Http\Controllers\CalendarHistorialController;
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

// Rutas protegidas (vistas)
Route::middleware(['auth'])->group(function() {

    // Calendario (vistas / pages)
    Route::get('/calendars', [CalendarController::class, 'index'])->name('calendars.index');
    Route::get('/calendars/create', [CalendarController::class, 'create'])->name('calendars.create'); 
    Route::post('/calendars', [CalendarController::class, 'store'])->name('calendars.store');        
    Route::get('/calendars/{calendar}', [CalendarController::class, 'show'])->name('calendars.show');

     Route::get('/api/calendar-events', [CalendarEventController::class, 'index']);
    Route::post('/api/calendar-events', [CalendarEventController::class, 'store']);
    Route::put('/api/calendar-events/{id}', [CalendarEventController::class, 'update']);
    Route::delete('/api/calendar-events/{id}', [CalendarEventController::class, 'destroy']);

    Route::get('/api/flat-members', [FlatMemberController::class, 'index']);

    Route::get('/api/calendar-history/exists', [CalendarHistorialController::class, 'existsForMonth']);
    Route::post('/api/calendar-history/clone', [CalendarHistorialController::class, 'cloneFromPrevious']);
    Route::post('/api/calendar-history/save', [CalendarHistorialController::class, 'saveSnapshot']);

});
