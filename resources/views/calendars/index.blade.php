{{-- resources/views/calendars/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Mis Calendarios - Chorely')

{{-- Indica al layout que NO muestre la topbar global (evita duplicados) --}}
@section('hide_topbar', true)

@section('content')

<div class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('calendars.index') }}" class="inline-block px-4 py-2 bg-emerald-50 text-emerald-600 rounded-lg">Mis calendarios</a>
        </div>
        <div>
            <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:underline">Volver a inicio</a>
        </div>
    </div>

    <h1 class="text-3xl font-bold mb-6 text-gray-900">Mis Calendarios</h1>

    @if($calendars->count() > 0)
        <div class="space-y-4 mb-6">
            @foreach($calendars as $calendar)
            <div class="bg-white p-8 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-200 flex flex-col">
                <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $calendar->name }}</h3>
                
                <div class="flex items-center gap-6 text-sm text-gray-600 mb-4 flex-wrap">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10h16V10" />
                        </svg>
                        <span class="font-medium">{{ $calendar->flat->name ?? 'Sin piso' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" />
                        </svg>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($calendar->month_start)->format('F Y') }}</span>
                    </div>
                </div>
                
                <p class="text-gray-500 text-sm mb-6">
                    Creado el {{ \Carbon\Carbon::parse($calendar->created_at)->format('d/m/Y') }}
                </p>
                
                <div class="flex justify-center mt-auto">
                    <a href="{{ route('calendars.show', $calendar->id) }}" 
                       class="px-6 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-all duration-200 font-medium">
                        Ver Calendario
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Paginación si la hubiera --}}
        <div class="mt-6">
            {{ $calendars->links() }}
        </div>

    @else
        <div class="text-center py-12">
            <div class="bg-gray-50 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7H3v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No tienes calendarios aún</h3>
            <p class="text-gray-600 mb-6">Crea tu primer calendario para comenzar a organizar las tareas del hogar</p>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('calendars.create') }}" 
           class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold py-3.5 px-6 rounded-xl hover:from-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg shadow-emerald-500/30 text-center">
            Crear Nuevo Calendario
        </a>
        
        @if($calendars->count() > 0)
        <a href="{{ route('calendars.show', $calendars->first()->id) }}" 
           class="flex-1 bg-white border-2 border-emerald-500 text-emerald-600 font-semibold py-3.5 px-6 rounded-xl hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 text-center">
            Ver Último Calendario
        </a>
        @endif
    </div>
</div>

@endsection
