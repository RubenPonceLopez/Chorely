@extends('layouts.admin')

@section('title','Admin - Usuarios')
@section('page_title','Usuarios')

@section('content')
@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
@endif

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-semibold">Lista de usuarios</h2>
        <p class="text-sm text-gray-600">Editar o eliminar usuarios registrados.</p>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 border rounded text-sm">Volver</a>
    </div>
</div>

{{-- Contenedor centrado --}}
<div class="flex justify-center">
    <div class="w-full max-w-5xl overflow-x-auto bg-white rounded p-4 shadow-sm">
        <table class="min-w-full border-collapse border">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-3 border text-left">ID</th>
                    <th class="px-4 py-3 border text-left">Nombre</th>
                    <th class="px-4 py-3 border text-left">Apellido</th>
                    <th class="px-4 py-3 border text-left">Email</th>
                    <th class="px-4 py-3 border text-left">Admin</th>
                    <th class="px-4 py-3 border text-left">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                <tr class="border-t align-top">
                    <td class="px-4 py-4 border align-top" style="width:60px;">{{ $u->id }}</td>
                    <td class="px-4 py-4 border align-top">{{ $u->name }}</td>
                    <td class="px-4 py-4 border align-top">{{ $u->apellido }}</td>
                    <td class="px-4 py-4 border align-top break-all">{{ $u->email }}</td>
                    <td class="px-4 py-4 border align-top" style="width:80px;">{{ $u->is_admin ? 'Sí' : 'No' }}</td>

                    {{-- Acciones: columna vertical (Editar encima, Eliminar debajo) --}}
                    <td class="px-4 py-4 border align-top" style="width:140px;">
                        <div class="flex flex-col items-start gap-2">
                            <a href="{{ route('admin.users.edit', $u) }}" class="px-3 py-1 bg-blue-600 text-white rounded text-sm">Editar</a>

                            {{-- Eliminar --}}
                            <form action="{{ route('admin.users.destroy', $u) }}" method="POST" onsubmit="return confirm('¿Eliminar usuario {{ $u->email }}? Esta acción es irreversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded text-sm">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
