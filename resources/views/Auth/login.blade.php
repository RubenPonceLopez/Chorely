@extends('layouts.app')

@section('title', 'Inicia Sesión con Chorely')

@section('content')
  <h1 class="text-3xl font-bold mb-6 text-gray-900">Inicia Sesión en Chorely</h1>

  {{-- Mensaje de éxito (flash) --}}
  @if(session('success'))
    <div role="status" aria-live="polite" class="mb-4 p-3 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-100">
      {{ session('success') }}
    </div>
  @endif

  {{-- Mensaje de estado (por ejemplo: "enlace enviado") --}}
  @if(session('status'))
    <div role="status" aria-live="polite" class="mb-4 p-3 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-100">
      {{ session('status') }}
    </div>
  @endif

  {{-- CAJITA SUPERIOR: errores del servidor --}}
  @if($errors->any())
    <div id="server-error-box" role="alert" aria-live="assertive" class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 shadow-sm">
      <div class="flex items-start gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12A9 9 0 1112 3a9 9 0 019 9z" />
        </svg>
        <div>
          <p class="font-semibold text-red-700">Ups — hay un problema con tu entrada</p>

          <ul class="mt-2 text-sm text-red-600 space-y-1">
            @foreach($errors->all() as $i => $error)
              @if($i < 10)
                <li>{{ $error }}</li>
              @endif
            @endforeach
            @if($errors->count() > 10)
              <li>...y {{ $errors->count() - 10 }} error(es) más.</li>
            @endif
          </ul>
        </div>
      </div>
    </div>
  @endif

  {{-- CAJITA SUPERIOR: errores del cliente (JS) - inicialmente oculta --}}
  <div id="client-error-box" role="alert" aria-live="assertive" class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 shadow-sm" style="display:none;">
    <div class="flex items-start gap-3">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12A9 9 0 1112 3a9 9 0 019 9z" />
      </svg>
      <div>
        <p class="font-semibold text-red-700" id="client-error-title">Ups — hay un problema con tu entrada</p>
        <ul class="mt-2 text-sm text-red-600 space-y-1" id="client-error-list">
          {{-- JS inyectará <li> aquí --}}
        </ul>
      </div>
    </div>
  </div>

  {{-- FORMULARIO --}}
  <form id="login-form" method="POST" action="{{ route('login.authenticate') }}" class="space-y-6" novalidate>
    @csrf

    <div>
      <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Correo electrónico</label>
      <input
        type="email"
        name="email"
        id="email"
        required
        value="{{ old('email') }}"
        autocomplete="email"
        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all duration-200 text-gray-900"
        placeholder="tu@email.com"
      >
      {{-- No mostramos errores debajo del campo (solo caja superior) --}}
    </div>

    <div>
      <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Contraseña</label>
      <input
        type="password"
        name="password"
        id="password"
        required
        autocomplete="current-password"
        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition-all duration-200 text-gray-900"
        placeholder="••••••••"
      >
      {{-- No mostramos errores debajo del campo (solo caja superior) --}}
    </div>

    <button type="submit"
      class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold py-3.5 px-6 rounded-xl hover:from-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg shadow-emerald-500/30">
      Iniciar Sesión
    </button>

    <div class="mt-6 flex items-center justify-between text-sm">
      <a href="{{ route('password.request') }}" class="text-emerald-600 hover:underline">¿Olvidaste tu contraseña?</a>
      <a href="{{ route('register') }}" class="text-emerald-600 hover:underline">¿Todavía no tienes cuenta? Regístrate gratis</a>
    </div>

    <div class="mt-6 text-center">
      <a href="{{ url('/') }}"
        class="inline-flex items-center justify-center w-full sm:w-auto bg-gradient-to-r from-emerald-300 to-teal-400 text-white font-semibold py-3.5 px-6 rounded-xl hover:from-emerald-400 hover:to-teal-500 transform hover:scale-[1.02] transition-all duration-200 shadow-md shadow-teal-300/30">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h16V10H4z" />
        </svg>
        Volver a la página principal
      </a>
    </div>
  </form>

  {{-- SCRIPT: validación cliente para email y mostrar cajita superior --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('login-form');
      const emailInput = document.getElementById('email');
      const clientBox = document.getElementById('client-error-box');
      const clientList = document.getElementById('client-error-list');
      const serverBox = document.getElementById('server-error-box');

      // Muestra la cajita de errores del cliente con los mensajes pasados
      function showClientError(messages) {
        // Oculta la caja del servidor para no confundir al usuario
        if (serverBox) serverBox.style.display = 'none';

        clientList.innerHTML = '';
        messages.forEach(msg => {
          const li = document.createElement('li');
          li.textContent = msg;
          clientList.appendChild(li);
        });

        clientBox.style.display = 'block';
        clientBox.classList.remove('hidden');
        // Opcional: foco para accesibilidad
        clientBox.setAttribute('tabindex', '-1');
        clientBox.focus({preventScroll: true});
      }

      // Si el usuario edita el email, ocultamos la caja cliente (para limpiar UX)
      if (emailInput) {
        emailInput.addEventListener('input', () => {
          if (clientBox) clientBox.style.display = 'none';
          if (serverBox) serverBox.style.display = 'block'; // si había errores del servidor, vuelve a mostrarlos
        });
      }

      if (!form) return;

      form.addEventListener('submit', function (e) {
        const email = (emailInput && emailInput.value || '').trim();

        // Regex simple que exige algo@algo.algo (útil para UX, no sustituye validación servidor)
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        const clientErrors = [];

        if (!email) {
          clientErrors.push('El campo correo electrónico es obligatorio.');
        } else if (!emailRegex.test(email)) {
          clientErrors.push('El campo correo electrónico debe ser una dirección de correo válida.');
        }

        if (clientErrors.length > 0) {
          e.preventDefault();
          showClientError(clientErrors);
          // Ligeramente desplazar la vista hacia la caja para que el usuario la vea
          clientBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
          return false;
        }

        // Si pasa la validación cliente, el formulario se envía y el servidor hace la validación definitiva
      });
    });
  </script>
@endsection
