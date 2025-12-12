{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Chorely')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 flex items-center justify-center p-4">

    <div class="w-full max-w-6xl flex flex-col lg:flex-row bg-white rounded-3xl shadow-2xl overflow-hidden">

        {{-- Panel Izquierdo - Branding --}}
        <div class="lg:w-1/2 bg-gradient-to-br from-emerald-500 to-teal-600 p-12 flex flex-col justify-between text-white">
            <div>
                <div class="flex items-center gap-3 mb-8">
                    <div class="bg-white/20 backdrop-blur-sm p-3 rounded-2xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l9-9 9 9M4 10v10h16V10" />
                        </svg>
                    </div>
                    <h1 class="text-4xl font-bold tracking-tight">Chorely</h1>
                </div>

                <h2 class="text-3xl font-semibold mb-4 leading-tight">
                    Organiza tu hogar compartido sin complicaciones
                </h2>

                <p class="text-emerald-50 text-lg mb-12 leading-relaxed">
                    Gestiona las tareas del hogar con tu equipo de forma simple y eficiente.
                    Calendarios compartidos, turnos automáticos y colaboración en tiempo real.
                </p>

                {{-- Características --}}
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="bg-white/20 backdrop-blur-sm p-2.5 rounded-xl flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Calendario Inteligente</h3>
                            <p class="text-emerald-50 text-sm">Organiza tareas por días y semanas de forma visual</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="bg-white/20 backdrop-blur-sm p-2.5 rounded-xl flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-emerald-50">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Gestión de Compañeros</h3>
                            <p class="text-emerald-50 text-sm">Asigna tareas equitativamente entre todos</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="bg-white/20 backdrop-blur-sm p-2.5 rounded-xl flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-1">Seguimiento Simple</h3>
                            <p class="text-emerald-50 text-sm">Marca tareas completadas y mantén todo bajo control</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-emerald-50 text-sm mt-12">
                © {{ date('Y') }} Chorely. Convivencia organizada.
            </div>
        </div>

        {{-- Panel Derecho - Contenido dinámico (login, register, etc.) --}}
        <div class="lg:w-1/2 p-12 flex flex-col justify-center">
            <div class="max-w-md mx-auto w-full">

                {{-- Top bar dentro del panel derecho (usuario + logout + admin link)
                     Sólo se muestra si la vista NO ha declarado @section('hide_topbar') --}}
                @unless(View::hasSection('hide_topbar'))
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-4">
                        <a href="{{ url('/') }}" class="text-emerald-600 font-medium">Chorely</a>
                    </div>

                    <div class="flex items-center gap-4">
                        @auth
                            <span class="text-sm text-gray-600">Hola, <strong>{{ auth()->user()->name }}</strong></span>

                            @if(auth()->user()->is_admin ?? false)
                                <a href="{{ url('/admin') }}"
                                   class="px-3 py-2 rounded bg-amber-50 text-amber-700 border border-amber-200 text-sm">
                                    Admin
                                </a>
                            @endif

                            <a href="{{ route('logout') }}" class="px-3 py-2 rounded bg-red-500 text-white text-sm">Cerrar Sesión</a>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}" class="px-3 py-2 rounded bg-emerald-600 text-white text-sm">Iniciar Sesión</a>
                        @endguest
                    </div>
                </div>
                @endunless

                @yield('content')
            </div>
        </div>
    </div>

    {{-- Hidden logout form (POST con CSRF) --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>

    {{-- Small JS helper: any <a href="/logout"> se convertirá en submit POST --}}
    <script>
      (function(){
        document.addEventListener('click', function(e){
          const a = e.target.closest && e.target.closest('a');
          if (!a) return;
          const href = a.getAttribute('href') || '';
          // Normaliza rutas que terminen en /logout o ?logout
          if (href.replace(window.location.origin, '').split('?')[0].endsWith('/logout')) {
            e.preventDefault();
            const form = document.getElementById('logout-form');
            if (form) form.submit();
          }
        }, true);
      })();
    </script>

</body>

</html>
