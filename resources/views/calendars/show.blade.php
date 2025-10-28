{{-- resources/views/calendars/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6">
  <div class="bg-white p-6 rounded-lg shadow">
    <h1 class="text-2xl font-semibold mb-4">Calendario: {{ $calendar->name }} — {{ \Carbon\Carbon::parse($calendar->month_start)->format('F Y') }}</h1>

    <!-- Contenedor con formulario rápido para añadir evento manual -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-600">Tarea (opcional)</label>
        <select id="taskSelect" class="w-full mt-1 p-2 border rounded">
          <option value="">-- Selecciona tarea --</option>
          @foreach($tasks as $task)
            <option value="{{ $task->id }}">{{ $task->name }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-600">Asignado a</label>
        <select id="userSelect" class="w-full mt-1 p-2 border rounded">
          <option value="">-- Selecciona usuario --</option>
          @foreach($users as $u)
            <option value="{{ $u->id }}">{{ $u->name }} {{ $u->apellido ?? '' }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-600">Fecha & hora</label>
        <input id="datetimeInput" type="datetime-local" class="w-full mt-1 p-2 border rounded">
      </div>

      <div class="md:col-span-3">
        <label class="block text-sm font-medium text-gray-600">Notas</label>
        <input id="notesInput" type="text" placeholder="Opcional" class="w-full mt-1 p-2 border rounded">
      </div>

      <div class="md:col-span-3 flex gap-2 mt-2">
        <button id="createEventBtn" class="px-4 py-2 bg-green-600 text-white rounded">Crear evento</button>
        <a href="{{ route('calendars.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Crear otro calendario</a>
      </div>
    </div>

    <div id="calendar" class="bg-white rounded shadow p-4"></div>

  </div>
</div>

<!-- Modal simple (oculto) para creación desde selección en FullCalendar -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
  <div class="bg-white p-4 rounded w-96">
    <h3 class="font-semibold mb-2">Crear evento</h3>
    <label class="block text-sm">Tarea</label>
    <select id="modalTask" class="w-full p-2 border rounded mb-2">@foreach($tasks as $task)<option value="{{ $task->id }}">{{ $task->name }}</option>@endforeach</select>
    <label class="block text-sm">Usuario</label>
    <select id="modalUser" class="w-full p-2 border rounded mb-2">@foreach($users as $u)<option value="{{ $u->id }}">{{ $u->name }} {{ $u->apellido ?? '' }}</option>@endforeach</select>
    <label class="block text-sm">Hora inicio</label>
    <input id="modalTime" type="time" class="w-full p-2 border rounded mb-2" value="09:00">
    <div class="flex justify-end gap-2">
      <button id="modalCancel" class="px-3 py-1 rounded border">Cancelar</button>
      <button id="modalSave" class="px-3 py-1 bg-green-600 text-white rounded">Guardar</button>
    </div>
  </div>
</div>

@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/main.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timegrid@6.1.8/main.min.css" rel="stylesheet" />
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.8/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/main.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const calendarId = {{ $calendar->id }};
  const calendarEl = document.getElementById('calendar');

  // helper: build ISO date string for event creation
  function isoFromDateTimeLocal(val) {
    if (!val) return null;
    // input type datetime-local returns 'YYYY-MM-DDTHH:MM'
    return val;
  }

  // Inicializar FullCalendar
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    initialDate: '{{ \Carbon\Carbon::parse($calendar->month_start)->toDateString() }}',
    selectable: true,
    editable: true,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    select: function(info) {
      // abrir modal para crear evento (prellenar fecha/hora)
      const modal = document.getElementById('modal');
      modal.dataset.date = info.startStr.slice(0,10);
      document.getElementById('modalTime').value = '09:00';
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    },
    eventClick: function(info) {
      // simple: confirmar borrar o marcar done
      if (confirm('¿Eliminar este evento?')) {
        fetch(`/events/${info.event.id}`, {
          method: 'DELETE',
          headers: {'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content}
        }).then(r=>r.json()).then(()=> {
          info.event.remove();
        });
      } else if (confirm('¿Marcar como completado?')) {
        fetch(`/events/${info.event.id}`, {
          method: 'PATCH',
          headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
          body: JSON.stringify({ status: 'done' })
        }).then(r=>r.json()).then(()=> {
          info.event.setProp('backgroundColor','#9AE6B4'); // verde suave
        });
      }
    },
    eventDrop: function(info) {
      // actualizar fecha (event_date)
      const startDate = info.event.start;
      const dateStr = startDate.toISOString().slice(0,10);
      const timeStr = startDate.toTimeString().slice(0,5);
      fetch(`/events/${info.event.id}`, {
        method: 'PATCH',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
        body: JSON.stringify({ event_date: dateStr, start_time: timeStr })
      }).then(r=>r.json()).then(()=> {
        // actualizado
      });
    },
    events: function(fetchInfo, successCallback, failureCallback) {
      // pedir resources + events -> aquí llamamos al endpoint que devuelve ambos
      fetch(`/calendars/${calendarId}/events?start=${fetchInfo.startStr}&end=${fetchInfo.endStr}`)
        .then(r=>r.json())
        .then(data => {
          // set resources? usamos resourceId simple; para esta demo mostramos events
          successCallback(data.events);
        }).catch(err => { failureCallback(err); });
    }
  });

  calendar.render();

  // Modal handlers
  document.getElementById('modalCancel').addEventListener('click', ()=> {
    const m = document.getElementById('modal');
    m.classList.add('hidden'); m.classList.remove('flex');
  });

  document.getElementById('modalSave').addEventListener('click', ()=> {
    const m = document.getElementById('modal');
    const date = m.dataset.date;
    const taskId = document.getElementById('modalTask').value;
    const userId = document.getElementById('modalUser').value;
    const time = document.getElementById('modalTime').value || '09:00';

    // build payload
    const payload = {
      task_id: taskId || null,
      assigned_user_id: userId || null,
      event_date: date,
      start_time: time,
      end_time: null,
      notes: ''
    };

    fetch(`/calendars/${calendarId}/events`, {
      method: 'POST',
      headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
      body: JSON.stringify(payload)
    }).then(r=>r.json()).then(data => {
      if (data.ok) {
        calendar.addEvent({
          id: data.event.id,
          title: data.event.title,
          start: data.event.start,
          end: data.event.end,
          resourceId: data.event.resourceId
        });
      } else {
        alert('Error al crear evento');
      }
      m.classList.add('hidden'); m.classList.remove('flex');
    }).catch(err => { console.error(err); alert('Error'); });
  });

  // Botón crear evento rápido (form arriba)
  document.getElementById('createEventBtn').addEventListener('click', (e)=> {
    e.preventDefault();
    const taskId = document.getElementById('taskSelect').value || null;
    const userId = document.getElementById('userSelect').value || null;
    const dt = document.getElementById('datetimeInput').value;
    const notes = document.getElementById('notesInput').value || '';

    if (!dt) return alert('Elige fecha y hora');

    const [date, time] = dt.split('T');
    const payload = {
      task_id: taskId,
      assigned_user_id: userId,
      event_date: date,
      start_time: time,
      end_time: null,
      notes: notes
    };

    fetch(`/calendars/${calendarId}/events`, {
      method: 'POST',
      headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
      body: JSON.stringify(payload)
    }).then(r=>r.json()).then(data => {
      if (data.ok) {
        calendar.addEvent({
          id: data.event.id,
          title: data.event.title,
          start: data.event.start,
          end: data.event.end,
          resourceId: data.event.resourceId
        });
        document.getElementById('taskSelect').value = '';
        document.getElementById('userSelect').value = '';
        document.getElementById('datetimeInput').value = '';
        document.getElementById('notesInput').value = '';
      } else {
        alert('Error creando evento');
      }
    }).catch(err=>{ console.error(err); alert('Error'); });
  });

});
</script>
@endsection
