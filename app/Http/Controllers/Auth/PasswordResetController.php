<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    // Muestra formulario para solicitar el enlace de reset
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email'); // resources/views/auth/passwords/email.blade.php
    }

    // Envía el email con el enlace (token)
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Password::sendResetLink devuelve status
        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            // devuelve mensaje (flash)
            return back()->with('status', __($status));
        }

        // error (usuario no encontrado u otro)
        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    // Muestra formulario donde el usuario pone la nueva contraseña (token en URL)
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with([
            'token' => $token,
            'email' => $request->query('email') // puede venir en query
        ]);
    }

    // Procesa el reseteo: valida token y cambia contraseña
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function ($user, $password) {
                // Aquí Laravel normalmente usa $user->setPasswordAttribute, but we must use password_hash
                $user->password_hash = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            // Loguea automáticamente (opcional) o redirige al login
            return redirect()->route('login')->with('success', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
