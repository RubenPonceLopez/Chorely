<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login'); // resources/views/login.blade.php
    }

    // Procesar credenciales y autenticar
    public function authenticate(Request $request)
    {
        // Validación: tercera posición son mensajes personalizados (aquí vacíos),
    // cuarta posición son los 'attribute names' que cambian el nombre mostrado.
    $credentials = $request->validate([
        'email' => ['required','email'],
        'password' => ['required'],
    ], [], [
        'email' => 'correo electrónico',
        'password' => 'contraseña',
    ]);

        // Debug: Verificar que las credenciales lleguen
        Log::info('Attempting login with email: ' . $credentials['email']);
        
        // Verificar si el usuario existe
        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            Log::info('User not found with email: ' . $credentials['email']);
            return back()->withErrors([
                'email' => 'No se encontró un usuario con ese correo electrónico.',
            ])->withInput();
        }
        
        Log::info('User found: ' . $user->name . ', ID: ' . $user->id);

        // Intentar autenticar con las credenciales usando el método estándar de Laravel
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            Log::info('Authentication successful for user: ' . $user->email);
            
            // Redirigir a calendars (listado)
            return redirect()->intended('/calendars')->with('success', 'Has iniciado sesión correctamente.');
        }

        Log::info('Authentication failed for user: ' . $credentials['email']);
        
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente.');
    }
}
