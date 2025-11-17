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
    
    {{-- Scripts --}}
    @stack('scripts')
</body>
</html>