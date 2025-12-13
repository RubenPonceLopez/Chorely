@extends('layouts.app')

@section('title', 'Crear Calendario - Chorely')

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

    <h1 class="text-3xl font-bold mb-6 text-gray-900">Crear Nuevo Calendario</h1>

    <form id="createCalendarForm" class="space-y-6" method="POST" novalidate>
        @csrf

        {{-- Nombre del calendario --}}
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Calendario</label>
            <input type="text" name="name" id="name" required
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition-all duration-200 text-gray-900"
                placeholder="Ejemplo: Calendario Enero 2025">
        </div>

        {{-- Nombre del piso --}}
        <div>
            <label for="flat_name" class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Piso</label>
            <input type="text" name="flat_name" id="flat_name" required
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition-all duration-200 text-gray-900"
                placeholder="Ejemplo: Piso Centro">
        </div>

        {{-- Mes de inicio --}}
        <div>
            <label for="month_start" class="block text-sm font-semibold text-gray-700 mb-2">Mes de Inicio</label>
            <input type="month" name="month_start" id="month_start" required
                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition-all duration-200 text-gray-900">
        </div>

        {{-- Participantes --}}
        <div id="participants" class="mt-2">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Participantes (nombre)</label>
            <div class="flex gap-2 mb-2 participant-row">
                <input name="participants[]" type="text"
                    class="participant-input flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition-all duration-200 text-gray-900"
                    placeholder="Nombre completo (ej: María Pérez)">
                <button type="button" id="addParticipant"
                    class="px-4 py-3 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200">+</button>
            </div>
            <p class="text-xs text-gray-500">Introduce los nombres de los participantes. Se crearán usuarios automáticamente si no existen.</p>
        </div>

        <button type="submit"
            class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold py-3.5 px-6 rounded-xl hover:from-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transform hover:scale-[1.02] transition-all duration-200 shadow-lg shadow-emerald-500/30">
            Crear Calendario
        </button>

        <div class="text-center">
            <a href="{{ route('calendars.index') }}" class="text-emerald-600 hover:underline text-sm">Ver calendarios existentes</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Añadir participante dinámicamente
    document.getElementById('addParticipant').addEventListener('click', function() {
        const container = document.getElementById('participants');
        const div = document.createElement('div');
        div.className = 'flex gap-2 mb-2 participant-row';
        div.innerHTML = `
            <input name="participants[]" type="text"
                class="participant-input flex-1 px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none transition-all duration-200 text-gray-900"
                placeholder="Nombre completo">
            <button type="button" class="removeBtn px-4 py-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-all duration-200">-</button>
        `;
        container.appendChild(div);
        div.querySelector('.removeBtn').addEventListener('click', () => div.remove());
    });

    // Envío AJAX
    document.getElementById('createCalendarForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const payload = {
            name: form.name.value.trim(),
            flat_name: form.flat_name.value.trim(),
            month_start: form.month_start.value, // ENVIAMOS YYYY-MM y lo convertimos en store()
            participants: Array.from(form.querySelectorAll('.participant-input'))
                .map(i => i.value.trim())
                .filter(v => v !== '')
        };

        if (!payload.name || !payload.flat_name || !payload.month_start) {
            alert('Completa todos los campos obligatorios.');
            return;
        }

        try {
            const response = await fetch('{{ route("calendars.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();
            if (data.ok) window.location.href = data.redirect;
            else alert('Error: ' + (data.message || 'No se pudo crear el calendario.'));
        } catch (err) {
            console.error(err);
            alert('Error interno al crear el calendario. Revisa la consola.');
        }
    });
});
</script>
@endsection
