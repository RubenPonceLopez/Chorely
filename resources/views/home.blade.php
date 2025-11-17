<!DOCTYPE html>
<html lang="es">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chorely - Home</title>
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 font-sans">

    {{-- NAVIGATION --}}
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-3">
                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-2 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V12H9v8a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9z"/></svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Chorely</span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="#about" class="px-4 py-2 text-gray-700 hover:text-emerald-600 font-medium transition-colors hidden sm:block">Sobre Nosotros</a>
                    <a href="{{ route('login') }}" class="px-5 py-2 text-emerald-600 hover:bg-emerald-50 font-semibold rounded-lg transition-all">Iniciar Sesión</a>
                    <a href="{{ route('register') }}" class="px-5 py-2 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition-all shadow-md">Registrarse</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 via-teal-500/10 to-cyan-500/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="grid lg:grid-cols-2 gap-12 items-center">

                {{-- IZQUIERDA --}}
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                        Organiza tu hogar de forma inteligente
                    </div>
                    <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Tu hogar compartido, <br>
                        <span class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">perfectamente organizado</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Gestiona tareas, coordina turnos y mantén la armonía en tu casa compartida. Simple, eficiente y sin complicaciones.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('register') }}" class="group px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-teal-700 transition-all shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transform hover:scale-[1.02] flex items-center justify-center gap-2">
                            Comenzar Gratis
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-white text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-all border-2 border-gray-200 hover:border-emerald-300 flex items-center justify-center gap-2">
                            Iniciar Sesión
                        </a>
                    </div>

                    <div class="mt-12 flex items-center gap-8 justify-center lg:justify-start text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Gratis para siempre</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h18M3 12h18M3 19h18"/></svg>
                            <span>Sin límite de usuarios</span>
                        </div>
                    </div>
                </div>

                {{-- DERECHA: Calendario mockup --}}
                <div class="relative">
                    <div class="relative bg-white rounded-3xl shadow-2xl p-8 border border-gray-200">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-gray-900">Calendario de Tareas</h3>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"/></svg>
                        </div>

                        {{-- Días de la semana --}}
                        <div class="grid grid-cols-7 gap-2 mb-4 text-xs font-semibold text-gray-500 text-center">
                            @foreach(['L','M','X','J','V','S','D'] as $day)
                                <div>{{ $day }}</div>
                            @endforeach
                        </div>

                        {{-- Mockup de días --}}
                        <div class="grid grid-cols-7 gap-2">
                            @for($i=1;$i<=35;$i++)
                                <div class="aspect-square rounded-lg flex items-center justify-center text-sm 
                                    @if($i==11) bg-gradient-to-br from-emerald-500 to-teal-600 text-white font-semibold shadow-lg
                                    @elseif($i==16) bg-emerald-100 text-emerald-700 font-medium
                                    @elseif($i==21) bg-teal-100 text-teal-700 font-medium
                                    @elseif($i>5 && $i<32) bg-gray-50 text-gray-700 hover:bg-gray-100 cursor-pointer transition-colors
                                    @else text-gray-300
                                    @endif">
                                    {{ ($i>5 && $i<32) ? $i-5 : '' }}
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- FEATURES SECTION --}}
    @include('partials.features')

    {{-- CTA SECTION --}}
    @include('partials.cta')


    {{-- FOOTER --}}
    @include('partials.footer')

</body>
</html>
