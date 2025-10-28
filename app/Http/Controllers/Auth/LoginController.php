<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login'); // resources/views/login.blade.php
    }

    // Procesar credenciales y autenticar (adaptado a password_hash)
    public function authenticate(Request $request)
    {

        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        // Buscar usuario por email
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No existe una cuenta con ese correo.'])->withInput();
        }

        // Comprobar password contra la columna password_hash
        if (!Hash::check($credentials['password'], $user->password_hash)) {
            return back()->withErrors(['password' => 'Credenciales incorrectas.'])->withInput();
        }

        // Loguear usuario
        Auth::login($user);

        // Redirigir a home/dashboard
        return redirect()->route('calendars.create')->with('success', 'Has iniciado sesión correctamente.');

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
