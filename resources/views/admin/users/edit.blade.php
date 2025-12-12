@extends('layouts.admin')

@section('title', 'Editar Usuario')
@section('page_title', 'Editar Usuario')

@section('content')

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white p-6 rounded shadow max-w-xl mx-auto">

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div>
            <label class="block font-semibold">Nombre</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full mt-1 border rounded p-2">
            @error('name')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Apellido --}}
        <div>
            <label class="block font-semibold">Apellido</label>
            <input type="text" name="apellido" value="{{ old('apellido', $user->apellido) }}" class="w-full mt-1 border rounded p-2">
            @error('apellido')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="block font-semibold">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full mt-1 border rounded p-2">
            @error('email')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Password --}}
        <div>
            <label class="block font-semibold">Nueva contraseña (dejar en blanco para no cambiar)</label>
            <input type="password" name="password" class="w-full mt-1 border rounded p-2">
            @error('password')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Is admin --}}
        <div class="flex items-center space-x-2">
            <input type="checkbox" name="is_admin" id="is_admin" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
            <label for="is_admin" class="font-medium">Administrador</label>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100">Cancelar</a>
            <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Guardar</button>
        </div>

    </form>

</div>

@endsection
