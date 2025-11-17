@extends('layouts.calendar')

@section('content')

<div class="max-w-full mx-auto p-6">
  <div class="flex items-center justify-between mb-6">
    <div>
      <a href="{{ route('calendars.index') }}" class="inline-block mr-3 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Volver a mis calendarios</a>
    </div>
    <div class="flex items-center gap-4">
      <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:underline">Volver a inicio</a>
    </div>
  </div>

  <div class="flex gap-6">
    <!-- Panel lateral: Tareas y Miembros -->
    <aside class="w-72 bg-white p-4 rounded shadow flex-shrink-0">
      <h3 class="font-semibold mb-2">Tareas</h3>
      <div id="tasks" class="space-y-2 mb-4">
        @isset($tasks)
          @foreach($tasks as $task)
            <div class="draggable-item task flex items-center p-2 rounded border cursor-grab"
                 data-type="task"
                 data-id="{{ $task->id }}"
                 data-name="{{ $task->name }}">
              <div class="text-sm">{{ $task->name }}</div>
            </div>
          @endforeach
        @else
          <div class="text-xs text-gray-500">No hay tareas disponibles</div>
        @endisset
      </div>

      <h3 class="font-semibold mb-2">Miembros</h3>
      <div id="participants" class="space-y-2">
        @foreach($flatMembers as $member)
          @php $u = $member->user; @endphp
          <div class="draggable-item participant flex items-center p-2 rounded border cursor-grab"
               data-type="participant"
               data-id="{{ $u->id }}"
               data-name="{{ $u->name }} {{ $u->apellido }}">
            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-3 text-xs">{{ strtoupper(substr($u->name,0,1)) }}</div>
            <div class="text-sm">{{ $u->name }} {{ $u->apellido }}</div>
          </div>
        @endforeach
      </div>

      <p class="text-xs text-gray-500 mt-3">Arrastra una tarea al calendario para crearla o asignarla.</p>
    </aside>

    <!-- Calendario -->
    <div class="flex-1 bg-white p-4 rounded shadow">
      <div id="calendar" class="w-full h-[600px]"></div>
    </div>
  </div>
</div>

<!-- Modal para asignar usuario y estado -->
<div id="userModal" style="display:none; position:fixed; top:30%; left:40%; background:white; padding:20px; border:1px solid #ccc; z-index:1000;">
  <h4 class="font-semibold mb-2">Asignar usuario y estado</h4>
  <label>Usuario:</label>
  <select id="userSelect" class="border rounded px-2 py-1 w-full mb-3"></select>
  <label>Estado:</label>
  <select id="statusSelect" class="border rounded px-2 py-1 w-full mb-3">
    <option value="pending">Pendiente</option>
    <option value="done">Completada</option>
  </select>
  <button id="assignBtn" class="bg-emerald-500 text-white px-4 py-2 rounded hover:bg-emerald-600">Asignar</button>
</div>

@endsection

@push('scripts')
<script>
  const CSRF_TOKEN = '{{ csrf_token() }}';
  const CALENDAR_ID = {{ $calendar->id }};
  const CALENDAR_INITIAL_DATE = '{{ $calendar->month_start }}'; // <- NUEVO: fecha de inicio del calendario
  const FLAT_MEMBERS = {!! json_encode($flatMembers->map(function($m){ return ['id'=>$m->user->id, 'name'=>$m->user->name.' '.$m->user->apellido]; })) !!};
  const CALENDAR_EVENTS_BASE_URL = '{{ url("/api/calendar-events") }}';
</script>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script src="{{ asset('js/calendar-chorely.js') }}?v={{ filemtime(public_path('js/calendar-chorely.js')) }}"></script>
@endpush
