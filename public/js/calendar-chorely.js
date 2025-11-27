// public/js/calendar-chorely.js
// Versión con deduplicación de eventos servidor/cliente y sin refetch().catch()
// FIX: evita duplicados al arrastrar y crear eventos (cliente + backend)

document.addEventListener('DOMContentLoaded', function() {

  // ---------- comprobaciones iniciales ----------
  if (typeof CALENDAR_EVENTS_BASE_URL === 'undefined' || typeof CALENDAR_ID === 'undefined') {
    console.error('Variables CALENDAR_EVENTS_BASE_URL o CALENDAR_ID no definidas.'); return;
  }

  // Inicializar draggable (si existe)
  try {
    const tasksEl = document.getElementById('tasks');
    if (tasksEl) {
      new FullCalendar.Draggable(tasksEl, {
        itemSelector: '.draggable-item.task',
        eventData: function(eventEl) {
          return {
            title: eventEl.dataset.name || eventEl.textContent.trim(),
            extendedProps: {
              taskId: eventEl.dataset.id,
              usuario: null,
              status: 'pending'
            }
          };
        }
      });
    }
  } catch (e) {
    console.warn('Draggable no disponible:', e);
  }

  const calendarEl = document.getElementById('calendar');
  if (!calendarEl) { console.error('#calendar no encontrado'); return; }

  // Helper: key para identificar eventos por fecha/task/usuario (dedupe)
  function eventKeyFromServerItem(item) {
    // item.start (YYYY-MM-DD), extendedProps.taskId, assigned_user_id
    const start = item.start ? item.start : (item.event_date || '');
    const taskId = (item.extendedProps && item.extendedProps.taskId) || item.task_id || '';
    const userId = (item.extendedProps && item.extendedProps.usuario) || item.assigned_user_id || '';
    return `${start}::${taskId}::${userId}`;
  }
  function eventKeyFromCalEvent(ev) {
    const start = ev.start ? ev.start.toISOString().slice(0,10) : '';
    const taskId = (ev.extendedProps && ev.extendedProps.taskId) || '';
    const userId = (ev.extendedProps && ev.extendedProps.usuario) || '';
    return `${start}::${taskId}::${userId}`;
  }

  // Construcción URL de eventos
  function buildEventsUrl(startISO, endISO) {
    return `${CALENDAR_EVENTS_BASE_URL}?calendar_id=${CALENDAR_ID}&start=${encodeURIComponent(startISO)}&end=${encodeURIComponent(endISO)}`;
  }

  // ---------- configuración del calendario ----------
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    initialDate: CALENDAR_INITIAL_DATE,
    droppable: true,
    editable: true,

    // ----------------------------
    // events: función que carga desde servidor PERO elimina duplicados
    // ----------------------------
    events: function(info, successCallback, failureCallback) {
      const url = buildEventsUrl(info.startStr, info.endStr);
      fetch(url, { credentials: 'same-origin' })
        .then(r => {
          if (!r.ok) throw new Error('HTTP ' + r.status);
          return r.json();
        })
        .then(serverEvents => {
          try {
            // Construir conjunto de "keys" de eventos que ya están en el calendario (cliente)
            // Incluye eventos temporales (_temp true) y permanentes.
            const clientKeys = {};
            calendar.getEvents().forEach(ev => {
              const k = eventKeyFromCalEvent(ev);
              if (k) clientKeys[k] = true;
            });

            // Filtrar serverEvents: si la key ya existe en clientKeys, omitimos el server event.
            // Esto es la clave para evitar que el evento creado por el cliente se "repita"
            // porque el backend también lo devolvería en la próxima consulta.
            const filtered = (serverEvents || []).filter(item => {
              const k = eventKeyFromServerItem(item);
              if (!k) return true; // si no podemos construir key, mantenerlo
              // Si en cliente existe la misma key, omitimos (preferimos el evento UI)
              return !clientKeys[k];
            });

            successCallback(filtered);
          } catch (e) {
            console.error('Error procesando eventos del servidor (dedupe):', e);
            // En caso de fallo, devolver los eventos originales (para no bloquear)
            successCallback(serverEvents);
          }
        })
        .catch(err => {
          console.error('Error cargando eventos:', err);
          failureCallback(err);
        });
    },

    // --------- EVENT RECEIVE: cuando sueltas un draggable en el calendario ----------
    eventReceive: function(info) {
      // info.event ya es creado por FullCalendar en la UI.
      // Marcamos como temporal y abrimos modal para asignar usuario/estado.
      info.event.setExtendedProp('_temp', true); // FIX: marca temporal
      openUserAssignModal(info.event, { isNew: true });
      // NOTA: NO hacemos refetch aquí para evitar que el backend "duplique" el evento.
    },

    eventClick: function(info) {
      openUserAssignModal(info.event, { isNew: false });
    },

    eventDrop: function(info) {
      // mover evento ya guardado / temporal
      updateEventDate(info.event);
    },

    eventDidMount: function(info) {
      // pinta segun estado/usuario
      if (info.event.extendedProps && info.event.extendedProps.status === 'done') {
        info.el.style.backgroundColor = '#28a745';
      } else if (info.event.extendedProps && info.event.extendedProps.usuario) {
        info.el.style.backgroundColor = getUserColor(info.event.extendedProps.usuario);
      }
    },

    // En datesSet limpiamos únicamente eventos temporales sin id
    datesSet: function(info) {
      try {
        // Eliminamos eventos `_temp` que no tienen id (por ejemplo si usuario cerró modal sin guardar)
        calendar.getEvents().forEach(ev => {
          if (ev.extendedProps && ev.extendedProps._temp && !ev.id) {
            ev.remove();
          }
        });
      } catch (e) { console.warn('datesSet cleanup error:', e); }
    }
  });

  calendar.render();

  // ---------------------------
  // Modal / UI: asignar usuario y status
  // ---------------------------
  const modal = document.getElementById('userModal');
  const userSelect = document.getElementById('userSelect');
  const statusSelect = document.getElementById('statusSelect');
  let currentEvent = null;
  let currentIsNew = false;

  function openUserAssignModal(event, opts = {}) {
    currentEvent = event;
    currentIsNew = !!opts.isNew;
    userSelect.innerHTML = '';
    if (Array.isArray(FLAT_MEMBERS)) {
      FLAT_MEMBERS.forEach(u => {
        let opt = document.createElement('option');
        opt.value = u.id;
        opt.text = u.name;
        userSelect.add(opt);
      });
    }
    userSelect.value = event.extendedProps && event.extendedProps.usuario ? event.extendedProps.usuario : '';
    statusSelect.value = event.extendedProps && event.extendedProps.status ? event.extendedProps.status : 'pending';
    modal.style.display = 'block';
  }

  document.getElementById('assignBtn').addEventListener('click', function() {
    if (!currentEvent) return;
    const userId = userSelect.value || null;
    const userName = (userSelect.options[userSelect.selectedIndex] || {}).text || '';
    const status = statusSelect.value || 'pending';

    // Actualizamos en UI
    currentEvent.setExtendedProp('usuario', userId);
    currentEvent.setExtendedProp('status', status);
    const baseTitle = (currentEvent.title || '').split(' - ')[0];
    const newTitle = userName ? `${baseTitle} - ${userName}` : baseTitle;
    currentEvent.setProp('title', newTitle);
    currentEvent.setProp('backgroundColor', status === 'done' ? '#28a745' : getUserColor(userId));

    // Si es nuevo (drop reciente) -> guardamos en servidor
    if (currentIsNew) {
      const payload = {
        calendar_id: CALENDAR_ID,
        task_id: currentEvent.extendedProps.taskId,
        assigned_user_id: userId,
        event_date: currentEvent.startStr,
        status: status
      };

      // Deshabilitar botón y marcar estado visual en modal si quieres
      const assignBtn = document.getElementById('assignBtn');
      assignBtn.disabled = true;
      assignBtn.textContent = 'Guardando...';

      // POST crear evento
      fetch(CALENDAR_EVENTS_BASE_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN
        },
        credentials: 'same-origin',
        body: JSON.stringify(payload)
      })
      .then(async r => {
        let data = null;
        try { data = await r.json(); } catch (e) { data = null; }

        if (!r.ok) {
          console.error('Error creando evento:', r.status, data);
          alert('Error creando evento. Revisa la consola.');
          // No hacemos refetch para evitar duplicados; en su lugar, dejamos el evento temporal.
          return;
        }

        // Si backend devuelve id, asignarlo al evento temporal y desmarcar temp.
        if (data && data.id) {
          currentEvent.setProp('id', data.id);
          currentEvent.setExtendedProp('_temp', false);
          // ya está persistido en BBDD y la UI reflecta el id -> futuras cargas no crearán duplicado
        } else {
          // si el backend no devolvió id, mantenemos temp (pero avisamos)
          console.warn('Creado pero no se devolvió id:', data);
        }

        // IMPORTANTE: NO hacemos calendar.refetchEvents() aquí.
        // Razon: refetch introduciría el evento backend además del temporal
        // y causaría duplicado. Hemos actualizado el evento temporal con el id
        // por lo que la UI ya está correcta.
      })
      .catch(err => {
        console.error('Error guardando evento:', err);
        alert('Error guardando el evento (ver consola).');
      })
      .finally(() => {
        assignBtn.disabled = false;
        assignBtn.textContent = 'Asignar';
      });

    } else {
      // evento existente -> PUT update
      if (currentEvent.id) {
        fetch(`${CALENDAR_EVENTS_BASE_URL}/${currentEvent.id}`, {
          method: 'PUT',
          headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF_TOKEN},
          credentials: 'same-origin',
          body: JSON.stringify({ assigned_user_id: userId, status: status, event_date: currentEvent.startStr })
        })
        .then(async r => {
          if (!r.ok) {
            const txt = await r.text().catch(()=>null);
            console.error('Error actualizando evento:', r.status, txt);
            alert('Error actualizando evento en servidor.');
          } else {
            // OK -> nada más; evitamos refetch para no sobrescribir estados temporales
          }
        })
        .catch(err => {
          console.error('Error en update event:', err);
          alert('Error actualizando evento.');
        });
      }
    }

    modal.style.display = 'none';
  });

  // cerrar modal cliqueando fuera
  window.addEventListener('click', function(e) {
    if (e.target === modal) modal.style.display = 'none';
  });

  // actualizar fecha al mover evento
  async function updateEventDate(event) {
    // si no tiene id -> evento temporal: actualizamos en UI solo
    if (!event.id) {
      // simple: mantenemos temporal con la nueva fecha (no guardado)
      return;
    }
    try {
      const r = await fetch(`${CALENDAR_EVENTS_BASE_URL}/${event.id}`, {
        method: 'PUT',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF_TOKEN},
        credentials: 'same-origin',
        body: JSON.stringify({ event_date: event.startStr })
      });
      if (!r.ok) {
        console.error('Error al actualizar fecha del evento:', r.status);
        alert('No se pudo actualizar la fecha en servidor.');
      }
    } catch (e) {
      console.error('Error updateEventDate:', e);
    }
  }

  function getUserColor(userId) {
    if (!userId) return '#3788d8';
    const colors = ['#3788d8','#ffc107','#fd7e14','#20c997','#6f42c1','#e83e8c'];
    const idx = Number(userId) || 0;
    return colors[idx % colors.length];
  }

  // ---------- Guardar snapshot (ya existente) ----------
  const saveBtn = document.getElementById('saveDistributionBtn');
  if (saveBtn) {
    saveBtn.addEventListener('click', function() {
      if (!confirm('¿Quieres guardar la distribución actual de este mes en el historial?')) return;

      saveBtn.disabled = true;
      saveBtn.textContent = 'Guardando...';

      fetch(CALENDAR_SAVE_HISTORY_URL, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': CSRF_TOKEN
        },
        body: JSON.stringify({ calendar_id: CALENDAR_ID })
      })
      .then(r => r.json())
      .then(resp => {
        if (resp.ok) {
          alert(resp.message || 'Distribución guardada correctamente.');
          // Opcional: tras guardar snapshot puedes decidir forzar recarga para
          // sincronizar UI con lo que haya en la BBDD (si lo deseas).
          // window.location.reload();
        } else {
          alert(resp.message || 'No se pudo guardar la distribución.');
        }
      })
      .catch(err => {
        console.error('Error guardando distribución:', err);
        alert('Error interno al guardar la distribución.');
      })
      .finally(() => {
        saveBtn.disabled = false;
        saveBtn.textContent = 'Guardar esta distribución';
      });
    });
  }

}); // DOMContentLoaded
