@extends('layouts.admin')

@section('title','Admin - Calendarios')
@section('page_title','Calendarios')

@section('content')
@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif

<div class="flex justify-between items-center mb-4">
    <div></div>
    {{-- Botón para volver al dashboard. Ajusta la ruta si tu nombre de ruta es distinto --}}
    <a href="{{ route('admin.dashboard') }}" class="inline-block px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">
        ← Volver al dashboard
    </a>
</div>

<div class="overflow-x-auto">
    <table class="min-w-full border-collapse border">
        <thead>
            <tr class="bg-gray-50">
                <th class="px-4 py-2 border">ID</th>
                <th class="px-4 py-2 border">Nombre</th>
                <th class="px-4 py-2 border">Piso</th>
                <th class="px-4 py-2 border">Mes inicio</th>
                <th class="px-4 py-2 border">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @php
                use Carbon\Carbon;
                $meses_es = [
                    '01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio',
                    '07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre'
                ];
            @endphp

            @foreach($calendars as $cal)
            <tr class="border-t">
                <td class="px-4 py-2 border">{{ $cal->id }}</td>

                {{-- Nombre: si existe name, mostrarlo; si no, mostrar "Calendario #ID" --}}
                <td class="px-4 py-2 border">
                    {{ $cal->name && trim($cal->name) !== '' ? $cal->name : "Calendario #{$cal->id}" }}
                </td>

                {{-- Mostrar nombre del piso si está disponible en la relación flat, si no mostrar flat_id o '-' --}}
                <td class="px-4 py-2 border">
                    @if(!empty($cal->flat) && !empty($cal->flat->name))
                        {{ $cal->flat->name }} {{-- nombre del flat --}}
                    @else
                        {{ $cal->flat_id ?? '-' }} {{-- id o guion --}}
                    @endif
                </td>

                {{-- Mes inicio: intentar parsear month_start --}}
                <td class="px-4 py-2 border">
                    @if(!empty($cal->month_start))
                        @php
                            $parsed = null;
                            try {
                                $parsed = Carbon::parse($cal->month_start);
                            } catch (\Exception $e) {
                                $parsed = null;
                            }
                        @endphp

                        @if($parsed)
                            {{-- Mostrar "Noviembre 2025" en español usando nuestro array --}}
                            @php
                                $m = $parsed->format('m');
                                $y = $parsed->format('Y');
                            @endphp
                            {{ $meses_es[$m] ?? $parsed->format('F') }} {{ $y }}
                        @else
                            {{ $cal->month_start }}
                        @endif
                    @else
                        -
                    @endif
                </td>

                <td class="px-4 py-2 border">
                    <a href="{{ route('admin.calendars.edit', $cal) }}" class="inline-block mr-2 px-3 py-1 bg-blue-600 text-white rounded text-sm">Editar</a>

                    <form action="{{ route('admin.calendars.destroy', $cal) }}" method="POST" class="inline" onsubmit="return confirm('Eliminar calendario?')">
                        @csrf
                        @method('DELETE')
                        <button class="inline-block px-3 py-1 bg-red-600 text-white rounded text-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $calendars->links() }}
</div>
@endsection
