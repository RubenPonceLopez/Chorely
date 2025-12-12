<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\FlatMemberController;
use App\Http\Controllers\CalendarHistorialController;
use App\Http\Controllers\CalendarEventController;

use App\Http\Controllers\AdminController; // Admin Controller

// ------------------------------------------------------
// HOME (PÚBLICO)
// ------------------------------------------------------
Route::get('/', function () {
    return view('home');
});

// ------------------------------------------------------
// AUTENTICACIÓN
// ------------------------------------------------------
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// RESET PASSWORD
Route::get('/password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

// ------------------------------------------------------
// RUTAS PROTEGIDAS (LOGIN NECESARIO)
// ------------------------------------------------------
Route::middleware(['auth'])->group(function() {

    // ---------------------------------------
    //            Z O N A   A D M I N
    // ---------------------------------------
    Route::prefix('admin')->name('admin.')->group(function () {

        // Dashboard
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

        // USERS CRUD
        Route::get('/users', [AdminController::class, 'usersIndex'])->name('users.index');
        Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        // CALENDARS CRUD
        Route::get('/calendars', [AdminController::class, 'calendarsIndex'])->name('calendars.index');
        Route::get('/calendars/{calendar}/edit', [AdminController::class, 'editCalendar'])->name('calendars.edit');
        Route::put('/calendars/{calendar}', [AdminController::class, 'updateCalendar'])->name('calendars.update');
        Route::delete('/calendars/{calendar}', [AdminController::class, 'destroyCalendar'])->name('calendars.destroy');
    });

    // ---------------------------------------
    //       RUTAS CALENDARIO NORMALES
    // ---------------------------------------
    Route::get('/calendars', [CalendarController::class, 'index'])->name('calendars.index');
    Route::get('/calendars/create', [CalendarController::class, 'create'])->name('calendars.create');
    Route::post('/calendars', [CalendarController::class, 'store'])->name('calendars.store');
    Route::get('/calendars/{calendar}', [CalendarController::class, 'show'])->name('calendars.show');

    // ---------------------------------------
    //        API EVENTOS
    // ---------------------------------------
    Route::get('/api/calendar-events', [CalendarEventController::class, 'index']);
    Route::post('/api/calendar-events', [CalendarEventController::class, 'store']);
    Route::put('/api/calendar-events/{id}', [CalendarEventController::class, 'update']);
    Route::delete('/api/calendar-events/{id}', [CalendarEventController::class, 'destroy']);

    // ---------------------------------------
    //     API MIEMBROS DEL PISO
    // ---------------------------------------
    Route::get('/api/flat-members', [FlatMemberController::class, 'index']);
    Route::post('/flats/{flat}/members', [FlatMemberController::class, 'store'])->name('flats.members.store');
    Route::delete('/flats/{flat}/members/{member}', [FlatMemberController::class, 'destroy'])->name('flats.members.destroy');

    // ---------------------------------------
    //        CALENDAR HISTORIAL
    // ---------------------------------------
    Route::get('/api/calendar-history/exists', [CalendarHistorialController::class, 'existsForMonth']);
    Route::post('/api/calendar-history/clone', [CalendarHistorialController::class, 'cloneFromPrevious']);
    Route::post('/api/calendar-history/save', [CalendarHistorialController::class, 'saveSnapshot']);

    // Nuevos
    Route::get('/api/calendar-history/list', [CalendarHistorialController::class, 'listSnapshots']);
    Route::post('/api/calendar-history/clone-from-historial', [CalendarHistorialController::class, 'cloneFromHistorial']);

    // Obtener o crear calendario para un mes dado
    Route::get('/calendar/get-or-create', [CalendarController::class, 'getOrCreateForMonth']);
    Route::get('/api/calendar/get-or-create', [CalendarController::class, 'getOrCreateForMonth']);

});
