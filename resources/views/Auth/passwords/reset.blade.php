@extends('layouts.app')

@section('title', 'Restablecer contraseña')

@section('content')
<h1 class="text-2xl font-bold mb-4">Restablecer contraseña</h1>

<form method="POST" action="{{ route('password.update') }}" class="space-y-4">
  @csrf

  <input type="hidden" name="token" value="{{ $token }}">
  <div>
    <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
    <input id="email" name="email" type="email" required value="{{ old('email', $email) }}"
      class="mt-1 block w-full rounded-md border-gray-300 p-2" />
    @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="password" class="block text-sm font-medium text-gray-700">Nueva contraseña</label>
    <input id="password" name="password" type="password" required class="mt-1 block w-full rounded-md border-gray-300 p-2" />
    @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
    <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-1 block w-full rounded-md border-gray-300 p-2" />
  </div>

  <button type="submit" class="w-full py-2 bg-emerald-600 text-white rounded-md">Restablecer contraseña</button>
</form>
@endsection
