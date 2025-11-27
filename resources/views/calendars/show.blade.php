@extends('layouts.calendar')

@section('content')

<!-- ============================================= -->
<!--   BOTONES SUPERIORES                          -->
<!-- ============================================= -->
<div class="w-full mt-8 mb-8">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-center gap-6">

        <!-- Botón Guardar distribución (SE MANTIENE igual) -->
        <button id="saveDistributionBtn"
            class="px-8 py-3 bg-emerald-500 text-white text-lg font-semibold rounded-xl shadow-lg hover:bg-emerald-600 transition-all duration-200">
            Guardar esta distribución
        </button>

        <!-- Botón para abrir modal de duplicar (restaurado color azul y forzado inline) -->
        <!-- Inline style garantiza el color aunque alguna clase se pierda -->
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


<!-- ============================================= -->
<!--   CONTENEDOR PRINCIPAL                        -->
<!-- ============================================= -->
<div class="flex gap-6">

    <!-- PANEL LATERAL -->
    <aside class="w-72 bg-white p-4 rounded shadow flex-shrink-0">
      <h3 class="font-semibold mb-2">Tareas</h3>

      <div id="tasks" class="space-y-2 mb-4">
        @foreach($tasks as $task)
          <div class="draggable-item task flex items-center p-2 rounded border cursor-grab"
               data-type="task"
               data-id="{{ $task->id }}"
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
               data-id="{{ $u->id }}"
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






<!-- ============================================= -->
<!--   MODAL ASIGNAR USUARIO                       -->
<!-- ============================================= -->
<div id="userModal"
     style="display:none; position:fixed; top:30%; left:40%; background:white; padding:20px; border:1px solid #ccc; z-index:1000;">
  <h4 class="font-semibold mb-2">Asignar usuario y estado</h4>

  <label>Usuario:</label>
  <select id="userSelect" class="border rounded px-2 py-1 w-full mb-3"></select>

  <label>Estado:</label>
  <select id="statusSelect" class="border rounded px-2 py-1 w-full mb-3">
    <option value="pending">Pendiente</option>
    <option value="done">Completada</option>
  </select>

  <button id="assignBtn"
    class="bg-emerald-500 text-white px-4 py-2 rounded hover:bg-emerald-600">Asignar</button>
</div>





<!-- ============================================= -->
<!--   MODAL NUEVO: ELEGIR MES A DUPLICAR          -->
<!--   - Botón "Duplicar" siempre presente y con  -->
<!--     color forzado inline para que se vea     -->
<!-- ============================================= -->
<div id="duplicateModal"
     style="display:none; position:fixed; top:15%; left:35%; background:white;
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

        <!-- BOTÓN DUPLICAR: forzado inline style (azul) + clases Tailwind -->
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
  const CALENDAR_HISTORY_EXISTS_URL = BASE_APP + '/api/calendar-history/exists';
  const CALENDAR_CLONE_URL = BASE_APP + '/api/calendar-history/clone';
  const CALENDAR_SAVE_HISTORY_URL = BASE_APP + '/api/calendar-history/save';

  // NUEVOS ENDPOINTS para duplicar manualmente
  const CALENDAR_HISTORY_LIST_URL = BASE_APP + '/api/calendar-history/list';
  const CALENDAR_CLONE_FROM_HISTORIAL_URL = BASE_APP + '/api/calendar-history/clone-from-historial';

  const FLAT_MEMBERS =
    {!! json_encode($flatMembers->map(fn($m) => ['id'=>$m->user->id, 'name'=>$m->user->name.' '.$m->user->apellido])) !!};
</script>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>

<!-- Tu JS del calendario (no tocado aquí) -->
<script src="{{ asset('js/calendar-chorely.js') }}?v={{ filemtime(public_path('js/calendar-chorely.js')) }}"></script>


<script>
/* ==========================================================
   LOGICA DEL MODAL DE DUPLICAR — Mejorada y robusta
   - Forzamos color azul inline para apariencia consistente
   - setCloneButtonEnabled controla disabled + clases visuales
   - Añadidos console.log para depuración
   ========================================================== */

document.addEventListener('DOMContentLoaded', function () {
  const duplicateModal = document.getElementById('duplicateModal');
  const openDuplicateModalBtn = document.getElementById('openDuplicateModalBtn');
  const closeDuplicateModal = document.getElementById('closeDuplicateModal');
  const snapshotSelect = document.getElementById('snapshotSelect');
  const cloneYearInput = document.getElementById('cloneYear');
  const cloneMonthInput = document.getElementById('cloneMonth');
  const cloneBtn = document.getElementById('cloneFromSnapshotBtn');

  // Defensas: avisar si falta algo crítico
  if (!openDuplicateModalBtn) {
    console.warn('openDuplicateModalBtn no encontrado — revisa el DOM.');
    return;
  }
  if (!snapshotSelect || !cloneBtn) {
    console.warn('Elementos del modal de duplicar no encontrados — revisa el DOM.');
    return;
  }

  // Helper visual: nunca quitamos bg-blue-500. Usamos opacity para mostrar disabled.
  function setCloneButtonEnabled(enabled) {
    if (enabled) {
      cloneBtn.disabled = false;
      cloneBtn.classList.remove('opacity-50','cursor-not-allowed');
      cloneBtn.classList.add('bg-blue-500','text-white');
      // force inline color to ensure visible
      cloneBtn.style.backgroundColor = '#2563eb';
      cloneBtn.style.color = '#ffffff';
    } else {
      cloneBtn.disabled = true;
      cloneBtn.classList.add('opacity-50','cursor-not-allowed');
      cloneBtn.classList.add('bg-blue-500','text-white');
      // keep inline color so it's still blue-looking even when opacified
      cloneBtn.style.backgroundColor = '#2563eb';
      cloneBtn.style.color = '#ffffff';
    }
  }

  // Inicializa los inputs año/mes destino con la fecha del calendario actual (comodidad)
  try {
    const d = new Date(CALENDAR_INITIAL_DATE);
    if (!isNaN(d.getTime())) {
      cloneYearInput.value = d.getFullYear();
      cloneMonthInput.value = d.getMonth() + 1;
    }
  } catch (e) {}

  // Abrir modal: cargar snapshots y poblar select
  openDuplicateModalBtn.addEventListener('click', () => {
      console.log('[dup] abrir modal duplicar — iniciando carga snapshots');
      duplicateModal.style.display = 'block';
      snapshotSelect.innerHTML = '<option value="">Cargando...</option>';
      setCloneButtonEnabled(false); // deshabilitado hasta comprobar que hay snapshots

      fetch(`${CALENDAR_HISTORY_LIST_URL}?flat_id=${FLAT_ID}`, { credentials: 'same-origin' })
          .then(r => {
              if (!r.ok) throw new Error('HTTP ' + r.status);
              return r.json();
          })
          .then(data => {
              console.log('[dup] respuesta snapshots:', data);
              const snaps = data && Array.isArray(data.snapshots) ? data.snapshots : (Array.isArray(data) ? data : []);
              snapshotSelect.innerHTML = '';

              if (!snaps || snaps.length === 0) {
                  const opt = document.createElement('option');
                  opt.value = '';
                  opt.textContent = 'No hay meses guardados';
                  snapshotSelect.appendChild(opt);
                  setCloneButtonEnabled(false);
                  return;
              }

              // Rellenar opciones del select
              const placeholder = document.createElement('option');
              placeholder.value = '';
              placeholder.textContent = 'Selecciona mes guardado...';
              snapshotSelect.appendChild(placeholder);

              snaps.forEach(s => {
                  const opt = document.createElement('option');
                  opt.value = s.id;
                  // texto legible: "MM/YYYY — guardado: fecha"
                  opt.textContent = `${String(s.month).padStart(2,'0')}/${s.year} — guardado: ${new Date(s.created_at).toLocaleString()}`;
                  opt.dataset.calendarId = s.calendar_id ?? '';
                  snapshotSelect.appendChild(opt);
              });

              // Si hay snapshots habilitamos el botón
              setCloneButtonEnabled(true);
          })
          .catch(err => {
              console.error('Error cargando snapshots:', err);
              snapshotSelect.innerHTML = '';
              const opt = document.createElement('option');
              opt.value = '';
              opt.textContent = 'Error al cargar (revisa la consola)';
              snapshotSelect.appendChild(opt);
              setCloneButtonEnabled(false);
          });
  });

  // Cerrar modal
  closeDuplicateModal?.addEventListener('click', () => {
      duplicateModal.style.display = 'none';
  });

  // Ejecutar clonación desde snapshot seleccionado
  cloneBtn.addEventListener('click', () => {

      const historialId = snapshotSelect.value;
      const targetYear = parseInt(cloneYearInput.value, 10);
      const targetMonth = parseInt(cloneMonthInput.value, 10);

      if (!historialId) {
          alert('Selecciona primero un mes guardado (snapshot).');
          return;
      }
      if (!targetYear || !targetMonth || targetMonth < 1 || targetMonth > 12) {
          alert('Introduce un año y mes destino válidos (mes entre 1 y 12).');
          return;
      }

      cloneBtn.disabled = true;
      cloneBtn.textContent = 'Duplicando...';

      fetch(CALENDAR_CLONE_FROM_HISTORIAL_URL, {
          method: 'POST',
          headers: {
              'Content-Type':'application/json',
              'X-CSRF-TOKEN': CSRF_TOKEN
          },
          credentials: 'same-origin',
          body: JSON.stringify({
              historial_id: historialId,
              target_year: targetYear,
              target_month: targetMonth
          })
      })
      .then(async r => {
          let json = null;
          try { json = await r.json(); } catch(e) { json = null; }
          if (!r.ok) {
              console.error('Error cloneFromHistorial response:', r.status, json);
              alert(json && json.message ? json.message : 'Error clonando (ver consola).');
              return;
          }

          if (json && json.ok && json.redirect) {
              window.location.href = json.redirect;
          } else if (json && json.ok) {
              window.location.reload();
          } else {
              alert(json && json.message ? json.message : 'La clonación no devolvió ok.');
          }
      })
      .catch(err => {
          console.error('Error clonando desde historial (catch):', err);
          alert('Error interno al clonar (ver consola).');
      })
      .finally(() => {
          cloneBtn.disabled = false;
          cloneBtn.textContent = 'Duplicar';
      });
  });

  // Forzar que el botón superior y el botón modal tengan color azul (defensa extra)
  try {
    const topBtn = document.getElementById('openDuplicateModalBtn');
    if (topBtn) {
      topBtn.classList.add('bg-blue-500','text-white');
      topBtn.style.backgroundColor = '#2563eb';
      topBtn.style.color = '#ffffff';
    }

    if (cloneBtn) {
      cloneBtn.classList.add('bg-blue-500','text-white');
      cloneBtn.style.backgroundColor = '#2563eb';
      cloneBtn.style.color = '#ffffff';
      // Si no había sido habilitado porque no hubo snaps, se mostrará opaco; es intencional
      if (!cloneBtn.disabled) cloneBtn.classList.remove('opacity-50','cursor-not-allowed');
    }
  } catch (e) {
    // no interrumpimos nada si falla
    console.warn('No se pudo forzar color botones:', e);
  }

}); // DOMContentLoaded
</script>


@endpush
