@extends('layouts.app')

@section('title', 'Recuperar contraseña')

@section('content')
<h1 class="text-2xl font-bold mb-4">¿Has olvidado la contraseña?</h1>

@if(session('status'))
  <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="space-y-4">
  @csrf
  <div>
    <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
    <input id="email" name="email" type="email" required value="{{ old('email') }}"
      class="mt-1 block w-full rounded-md border-gray-300 p-2" />
    @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <button type="submit" class="w-full py-2 bg-emerald-600 text-white rounded-md">Enviar enlace de recuperación</button>

  <p class="text-sm text-gray-600 mt-3">Recibirás un email con un enlace para restablecer tu contraseña.</p>
</form>
@endsection
