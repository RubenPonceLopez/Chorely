<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login'); // resources/views/auth/login.blade.php
    }

    // Procesar credenciales y autenticar
    public function authenticate(Request $request)
    {
        // Validación
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ], [], [
            'email' => 'correo electrónico',
            'password' => 'contraseña',
        ]);

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

            // Si es admin, redirige al panel admin
            if (Auth::user()->is_admin ?? false) {
                return redirect()->intended('/admin')->with('success', 'Has iniciado sesión como administrador.');
            }

            // Si no, comportamiento normal
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
