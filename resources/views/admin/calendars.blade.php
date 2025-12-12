@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Calendarios</h1>

    <table class="w-full border">
        <tr class="bg-gray-100">
            <th class="border p-2">ID</th>
            <th class="border p-2">Año</th>
            <th class="border p-2">Mes</th>
            <th class="border p-2">Flat</th>
        </tr>

        @foreach ($calendars as $cal)
            <tr>
                <td class="border p-2">{{ $cal->id }}</td>
                <td class="border p-2">{{ $cal->year }}</td>
                <td class="border p-2">{{ $cal->month }}</td>
                <td class="border p-2">{{ $cal->flat_id }}</td>
            </tr>
        @endforeach
    </table>
</div>
@endsection
