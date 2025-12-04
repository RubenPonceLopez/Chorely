<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Calendario - Chorely')</title>
    
    {{-- Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Estilos adicionales --}}
    @stack('styles')

    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            background: #f3f4f6;
        }
    </style>
</head>
<body>
    @include('partials.navbar')
    @yield('content')
    
    {{-- Hidden logout form (POST con CSRF) --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>

    {{-- Scripts --}}
    @stack('scripts')

    {{-- Small JS helper: any <a href="/logout"> se convertirá en submit POST (evita 419) --}}
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
