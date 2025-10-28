@extends('layouts.app')

@section('title', 'Regístrate en Chorely')

@section('content')
<h1 class="text-3xl font-bold mb-6 text-gray-900">Registro de Usuario</h1>

{{-- Errores de validación --}}
@if ($errors->any())
    <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
        <strong class="block mb-2">Corrige los siguientes errores:</strong>
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Errores devueltos explícitamente desde el controlador --}}
@if (session('error'))
    <div class="mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
        {{ session('error') }}
    </div>
@endif

<form method="POST" action="{{ route('register.store') }}" class="space-y-6">
    @csrf

    <div>
        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nombre</label>
        <input
            type="text"
            name="name"
            id="name"
            required
            value="{{ old('name') }}"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all duration-200 text-gray-900"
            placeholder="Tu nombre"
        >
    </div>

    <div>
        <label for="apellido" class="block text-sm font-semibold text-gray-700 mb-2">Apellido</label>
        <input
            type="text"
            name="apellido"
            id="apellido"
            required
            value="{{ old('apellido') }}"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all duration-200 text-gray-900"
            placeholder="Tu apellido"
        >
    </div>

    <div>
        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Correo electrónico</label>
        <input
            type="email"
            name="email"
            id="email"
            required
            value="{{ old('email') }}"
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all duration-200 text-gray-900"
            placeholder="tu@email.com"
        >
    </div>

    <div>
        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Contraseña</label>
        <input
            type="password"
            name="password"
            id="password"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all duration-200 text-gray-900"
            placeholder="••••••••"
        >
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirmar Contraseña</label>
        <input
            type="password"
            name="password_confirmation"
            id="password_confirmation"
            required
            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all duration-200 text-gray-900"
            placeholder="••••••••"
        >
    </div>

    <button type="submit"
            class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold py-3.5 px-6 rounded-xl hover:from-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg shadow-emerald-500/30">
        Registrarse
    </button>

    <a href="{{ route('login') }}"
       class="w-full block text-center mt-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold py-3.5 px-6 rounded-xl hover:from-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 transform hover:scale-[1.02] transition-all duration-200 shadow-lg shadow-emerald-500/30">
        ¿Ya tienes cuenta?
    </a>
</form>
@endsection
