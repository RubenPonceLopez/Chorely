@extends('layouts.admin')

@section('title', 'Editar Calendario')
@section('page_title', 'Editar Calendario')

@section('content')

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white p-6 rounded shadow max-w-xl mx-auto">

    <form action="{{ route('admin.calendars.update', $calendar) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div>
            <label class="block font-semibold">Nombre del calendario</label>
            <input type="text" name="name"
                   value="{{ old('name', $calendar->name) }}"
                   class="w-full mt-1 border rounded p-2">
            @error('name') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Año --}}
        <div>
            <label class="block font-semibold">Año</label>
            <input type="number" name="year"
                   value="{{ old('year', $calendar->year) }}"
                   class="w-full mt-1 border rounded p-2">
            @error('year') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Mes --}}
        <div>
            <label class="block font-semibold">Mes (1–12)</label>
            <input type="number" name="month"
                   value="{{ old('month', $calendar->month) }}"
                   class="w-full mt-1 border rounded p-2">
            @error('month') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Piso --}}
        <div>
            <label class="block font-semibold">Piso</label>
            <select name="flat_id" class="w-full mt-1 border rounded p-2">
                <option value="">— Sin piso asociado —</option>
                @foreach(\App\Models\Flat::all() as $flat)
                    <option value="{{ $flat->id }}" {{ (old('flat_id', $calendar->flat_id) == $flat->id) ? 'selected' : '' }}>
                        {{ $flat->name }}
                    </option>
                @endforeach
            </select>
            @error('flat_id') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin.calendars.index') }}" class="px-4 py-2 border rounded hover:bg-gray-100">Cancelar</a>
            <button class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Guardar</button>
        </div>

    </form>

</div>

@endsection
