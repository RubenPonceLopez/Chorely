@extends('layouts.calendar')

@section('content')

<!-- ============================================= -->
<!--   BOTONES SUPERIORES                          -->
<!-- ============================================= -->
<div class="w-full mt-8 mb-8">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-6">
        <!-- Guardar definitivo -->
        <button id="saveDistributionBtn"
            class="px-8 py-3 bg-emerald-500 text-white text-lg font-semibold rounded-xl shadow-lg hover:bg-emerald-600 transition-all duration-200">
            Guardar esta distribución
        </button>

        <!-- Guardar borrador -->
        <button id="saveDraftBtn"
            class="px-8 py-3 bg-yellow-500 text-white text-lg font-semibold rounded-xl shadow-lg hover:bg-yellow-600 transition-all duration-200"
            style="background-color:#f59e0b;">
            Guardar borrador
        </button>

        <!-- Botón duplicar -->
        <button id="openDuplicateModalBtn"
            class="px-8 py-3 bg-blue-500 text-white text-lg font-semibold rounded-xl shadow-lg hover:bg-blue-600 transition-all duration-200"
            style="background-color:#2563eb; color:#ffffff;">
            Duplicar mes guardado
        </button>

        <!-- Enlaces -->
        <div class="flex items-center gap-10">
            <a href="{{ route('calendars.index') }}"
               class="text-gray-700 hover:text-gray-900 hover:underline transition">
               Volver a mis calendarios
            </a>

            <a href="{{ url('/') }}"
               class="text-gray-700 hover:text-gray-900 hover:underline transition">
               Volver a inicio
            </a>
        </div>

    </div>
</div>

<div class="flex gap-6">

    <!-- PANEL LATERAL -->
    <aside class="w-72 bg-white p-4 rounded shadow flex-shrink-0">
      <h3 class="font-semibold mb-2">Tareas</h3>

      <div id="tasks" class="space-y-2 mb-4">
        @foreach($tasks as $task)
          <div class="draggable-item task flex items-center p-2 rounded border cursor-grab"
               data-task-id="{{ $task->id }}"
               data-name="{{ $task->name }}">
            <div class="text-sm">{{ $task->name }}</div>
          </div>
        @endforeach
      </div>

      <h3 class="font-semibold mb-2">Miembros</h3>

      <div id="participants" class="space-y-2">
        @foreach($flatMembers as $member)
          @php $u = $member->user; @endphp
          <div class="draggable-item participant flex items-center p-2 rounded border cursor-grab"
               data-user-id="{{ $u->id }}"
               data-name="{{ $u->name }} {{ $u->apellido }}">
            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3 text-xs">
              {{ strtoupper(substr($u->name,0,1)) }}
            </div>
            <div class="text-sm">{{ $u->name }} {{ $u->apellido }}</div>
          </div>
        @endforeach
      </div>

      <p class="text-xs text-gray-500 mt-3">Arrastra una tarea al calendario para crearla o asignarla.</p>
    </aside>

    <!-- CALENDARIO -->
    <div class="flex-1 bg-white p-4 rounded shadow">
      <div id="calendar" class="w-full h-[600px]"></div>
    </div>

</div>

<!-- BACKDROP modal global (para cerrar clicando fuera) -->
<div id="userModalBackdrop" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:999;"></div>

<!-- ============================================= -->
<!--   MODAL ASIGNAR USUARIO (ampliado con horas)  -->
<!-- ============================================= -->
<div id="userModal"
     style="display:none; position:fixed; top:30%; left:50%; transform:translateX(-50%); background:white; padding:20px; border:1px solid #ccc; z-index:10000; width:420px; border-radius:8px;">
  <h4 class="font-semibold mb-2">Asignar usuario y estado</h4>

  <label for="userSelect">Usuario:</label>
  <select id="userSelect" class="border rounded px-2 py-1 w-full mb-3"></select>

  <label for="statusSelect">Estado:</label>
  <select id="statusSelect" class="border rounded px-2 py-1 w-full mb-3">
    <option value="pending">Pendiente</option>
    <option value="done">Completada</option>
  </select>

  <div class="flex gap-3 mb-3">
    <div class="flex-1">
      <label for="startTimeInput">Hora inicio:</label>
      <input id="startTimeInput" type="time" class="border rounded px-2 py-1 w-full"/>
    </div>
    <div class="flex-1">
      <label for="endTimeInput">Hora fin:</label>
      <input id="endTimeInput" type="time" class="border rounded px-2 py-1 w-full"/>
    </div>
  </div>

  <div class="flex justify-end gap-3">
    <button id="cancelAssignBtn" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
    <button id="assignBtn" class="bg-emerald-500 text-white px-4 py-2 rounded hover:bg-emerald-600">Asignar</button>
  </div>
</div>

<!-- ============================================= -->
<!--   MODAL DUPLICAR                               -->
<!-- ============================================= -->
<div id="duplicateModal"
     style="display:none; position:fixed; top:15%; left:50%; transform:translateX(-50%); background:white;
            width:450px; padding:25px; border:1px solid #ccc; z-index:5000;
            box-shadow:0 0 20px rgba(0,0,0,0.3); border-radius:10px;">

    <h3 class="text-xl font-bold mb-4">Duplicar un mes guardado</h3>

    <p class="text-sm text-gray-600 mb-3">
        Selecciona un mes guardado y elige a qué mes deseas copiar su distribución.
    </p>

    <!-- Lista de snapshots -->
    <label class="font-semibold">Mes guardado:</label>
    <select id="snapshotSelect"
            class="border rounded px-3 py-2 w-full mb-4">
        <option value="">Cargando...</option>
    </select>

    <div class="flex gap-3">
        <div class="flex-1">
            <label class="font-semibold">Año destino:</label>
            <input type="number" id="cloneYear" class="border rounded px-3 py-2 w-full" min="2023" max="2100" />
        </div>

        <div class="flex-1">
            <label class="font-semibold">Mes destino:</label>
            <input type="number" id="cloneMonth" class="border rounded px-3 py-2 w-full" min="1" max="12" />
        </div>
    </div>

    <div class="mt-6 flex justify-end gap-4">
        <button id="closeDuplicateModal"
            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>

        <button id="cloneFromSnapshotBtn"
            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
            style="background-color:#2563eb; color:#ffffff;">
            Duplicar
        </button>
    </div>

</div>

@endsection

@push('scripts')
<script>
  const CSRF_TOKEN = '{{ csrf_token() }}';
  const CALENDAR_ID = {{ $calendar->id }};
  const CALENDAR_INITIAL_DATE = '{{ $calendar->month_start }}';
  const FLAT_ID = {{ (int) $calendar->flat_id }};
  const BASE_APP = '{{ url("") }}';

  const CALENDAR_EVENTS_BASE_URL = BASE_APP + '/api/calendar-events';
  const CALENDAR_HISTORY_LIST_URL = BASE_APP + '/api/calendar-history/list';
  const CALENDAR_CLONE_FROM_HISTORIAL_URL = BASE_APP + '/api/calendar-history/clone-from-historial';
  const CALENDAR_SAVE_HISTORY_URL = BASE_APP + '/api/calendar-history/save';

  const FLAT_MEMBERS =
    {!! json_encode($flatMembers->map(fn($m) => ['id'=>$m->user->id, 'name'=>$m->user->name.' '.$m->user->apellido])) !!};
</script>

<!-- FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<!-- Tu script -->
<script src="{{ asset('js/calendar-chorely.js') }}?v={{ filemtime(public_path('js/calendar-chorely.js')) }}"></script>
@endpush
