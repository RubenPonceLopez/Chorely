{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Chorely')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-emerald-50 p-6">

    <div class="max-w-6xl mx-auto bg-white rounded-xl shadow p-6">

        {{-- Top bar: título y logout --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold">@yield('page_title','Panel de administración')</h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600 hidden sm:inline">Hola, <strong>{{ auth()->user()->name }}</strong></span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="px-3 py-2 rounded bg-red-500 text-white text-sm">Cerrar Sesión</button>
                </form>
            </div>
        </div>

        {{-- Contenido principal --}}
        <div>
            @yield('content')
        </div>

    </div>

</body>
</html>
