@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Usuarios</h1>

    <table class="w-full border">
        <tr class="bg-gray-100">
            <th class="border p-2">ID</th>
            <th class="border p-2">Nombre</th>
            <th class="border p-2">Apellido</th>
            <th class="border p-2">Email</th>
            <th class="border p-2">Admin</th>
        </tr>

        @foreach ($users as $user)
            <tr>
                <td class="border p-2">{{ $user->id }}</td>
                <td class="border p-2">{{ $user->name }}</td>
                <td class="border p-2">{{ $user->apellido }}</td>
                <td class="border p-2">{{ $user->email }}</td>
                <td class="border p-2">{{ $user->is_admin }}</td>
            </tr>
        @endforeach
    </table>
</div>
@endsection
