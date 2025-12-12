@extends('layouts.admin')

@section('title','Admin - Dashboard')
@section('page_title','Panel de administrador')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-2">Panel de administrador</h2>
        <p class="text-sm text-gray-600 mb-4">Elige una sección para gestionar la aplicación.</p>

        {{-- Contenedor vertical: Usuarios encima, Calendarios debajo --}}
        <div class="flex flex-col space-y-4">
            {{-- Card: Usuarios --}}
            <a href="{{ route('admin.users.index') }}" class="block">
                <div class="w-full border rounded-lg p-5 hover:shadow-lg transition-shadow duration-150 flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-indigo-50 rounded-md flex items-center justify-center">
                            <svg class="h-6 w-6 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.761 0 5.303.833 7.379 2.266M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-base font-semibold">Usuarios</h3>
                        <p class="text-sm text-gray-600">Ver, editar y eliminar usuarios registrados.</p>
                    </div>

                    <div class="ml-auto hidden sm:flex items-center">
                        <span class="text-sm text-indigo-600 font-medium">Ir →</span>
                    </div>
                </div>
            </a>

            {{-- Card: Calendarios --}}
            <a href="{{ route('admin.calendars.index') }}" class="block">
                <div class="w-full border rounded-lg p-5 hover:shadow-lg transition-shadow duration-150 flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 bg-green-50 rounded-md flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-base font-semibold">Calendarios</h3>
                        <p class="text-sm text-gray-600">Ver, editar y eliminar calendarios del sistema.</p>
                    </div>

                    <div class="ml-auto hidden sm:flex items-center">
                        <span class="text-sm text-green-600 font-medium">Ir →</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
