@extends('layouts.app')

@section('title', 'Inicia Sesión con Chorely')

@section('content')

    <h1 class="text-3xl font-bold mb-6 text-gray-900">Inicia Sesión en Chorely</h1>


    <form method="POST" action="{{ route('login.authenticate') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Correo electrónico</label>
            <input type="email" name="email" id="email" required
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all duration-200 text-gray-900"
                placeholder="tu@email.com">
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Contraseña</label>
            <input type="password" name="password" id="password" required
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all duration-200 text-gray-900"
                placeholder="••••••••">
        </div>


        <button type="submit"
            class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold py-3.5 px-6 rounded-xl hover:from-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg shadow-emerald-500/30">
            Iniciar Sesión
        </button>

        <!-- después del botón submit -->
        <div class="mt-6 flex items-center justify-between text-sm">
            <a href="{{ route('password.request') }}" class="text-emerald-600 hover:underline">¿Olvidaste tu contraseña?</a>
            <a href="{{ route('register') }}" class="text-emerald-600 hover:underline">¿Todavía no tienes cuenta? Regístrate
                gratis</a>
        </div>

    </form>
@endsection
