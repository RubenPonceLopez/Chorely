<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class RegisterController extends Controller
{
    // Mostrar formulario de registro
    public function index()
    {
        return view('auth.register');
    }

    // Procesar registro
    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ]);

        try {
            // Crear usuario
            $user = User::create([
                'name' => $validated['name'],
                'apellido' => $validated['apellido'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            if (! $user || ! $user->id) {
                Log::error('Usuario creado pero no se devolvió ID.', ['user' => $user]);
                return back()->withInput()->withErrors(['error' => 'No se pudo crear el usuario. Inténtalo más tarde.']);
            }

            return redirect()->route('login')->with('success', 'Cuenta creada correctamente. Ya puedes iniciar sesión.');
        } catch (QueryException $e) {
            Log::error('Error al crear usuario (QueryException): '.$e->getMessage(), [
                'code' => $e->getCode(),
                'sql' => $e->getSql(),
            ]);
            return back()->withInput()->withErrors(['error' => 'Error en la base de datos al crear la cuenta. Revisa logs.']);
        } catch (\Exception $e) {
            Log::error('Error inesperado al crear usuario: '.$e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Error inesperado. Revisa logs o contacta con soporte.']);
        }
    }
}
