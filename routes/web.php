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

    // API eventos de calendario (CRUD)
    // (estas rutas ya usan el prefijo /api en el frontend)
    Route::get('/api/calendar-events', [CalendarEventController::class, 'index']);
    Route::post('/api/calendar-events', [CalendarEventController::class, 'store']);
    Route::put('/api/calendar-events/{id}', [CalendarEventController::class, 'update']);
    Route::delete('/api/calendar-events/{id}', [CalendarEventController::class, 'destroy']);

    // Miembros del piso (API)
    Route::get('/api/flat-members', [FlatMemberController::class, 'index']);

    // Añadir miembro al piso
    Route::post('/flats/{flat}/members', [FlatMemberController::class, 'store'])
         ->name('flats.members.store');

    // Eliminar miembro
    Route::delete('/flats/{flat}/members/{member}', [FlatMemberController::class, 'destroy'])
         ->name('flats.members.destroy');

    // ENDPOINTS relacionados con el historial de calendarios (calendar_historial)
    // Existencia de calendario para mes/flat (usado por comprobaciones)
    Route::get('/api/calendar-history/exists', [CalendarHistorialController::class, 'existsForMonth']);

    // Clonar desde un calendario existente (antiguo comportamiento)
    Route::post('/api/calendar-history/clone', [CalendarHistorialController::class, 'cloneFromPrevious']);

    // Guardar snapshot del calendario actual en calendar_historial
    Route::post('/api/calendar-history/save', [CalendarHistorialController::class, 'saveSnapshot']);

    // ----------------------------------------------------------------
    // NUEVAS RUTAS (añadidas)
    // 1) Listar snapshots guardados para un flat (para poblar modal "Duplicar mes")
    // 2) Clonar desde un snapshot guardado (clone-from-historial)
    // ----------------------------------------------------------------
    // Lista snapshots guardados (calendar_historial) para un flat_id
    Route::get('/api/calendar-history/list', [CalendarHistorialController::class, 'listSnapshots']); // NUEVO

    // Clonar desde un historial ya guardado: { historial_id, target_year, target_month }
    Route::post('/api/calendar-history/clone-from-historial', [CalendarHistorialController::class, 'cloneFromHistorial']); // NUEVO

    // Obtener o crear calendario para un mes dado
    // (ruta 'normal' que tenías)
    Route::get('/calendar/get-or-create', [CalendarController::class, 'getOrCreateForMonth']);

    // ---- Para compatibilidad con el frontend que pide /api/calendar/get-or-create ----
    // Añadimos la misma acción también bajo /api/* para evitar 404 sin tocar JS
    Route::get('/api/calendar/get-or-create', [CalendarController::class, 'getOrCreateForMonth']);

});
