// public/js/calendar-chorely.js
document.addEventListener('DOMContentLoaded', function() {

  //Manejo del evento draggablec(Permite arrastrar tareas al calendario y asignarle propiedades (usuario, estado etc))

  new FullCalendar.Draggable(document.getElementById('tasks'), {
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

    //Pedidio de eventos al backend

  let calendarEl = document.getElementById('calendar');

  function buildEventsUrl(startDate, endDate) {
    const s = encodeURIComponent(startDate.toISOString());
    const e = encodeURIComponent(endDate.toISOString());
    return `${CALENDAR_EVENTS_BASE_URL}?calendar_id=${CALENDAR_ID}&start=${s}&end=${e}`;
  }


  //Configuración del calendario de la librería "FullCalendar"

  let calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    initialDate: CALENDAR_INITIAL_DATE,
    droppable: true,  // Permite soltar elementos arrastrados
    editable: true,  // Permite editar eventos (arrastrar, soltar, etc.)


      //Carga de eventos desde el servidor 
    events: function(info, successCallback, failureCallback) {
      const url = buildEventsUrl(info.start, info.end);
      fetch(url, { credentials: 'same-origin' })
        .then(r => {
          if (!r.ok) throw new Error('HTTP ' + r.status);
          return r.json();
        })
        .then(data => successCallback(data))
        .catch(err => {
          console.error('Error cargando eventos:', err);
          failureCallback(err);
        });
    },


      //Manejadores de interacción de eventos (recibir, click, drop))
    eventReceive: function(info) {  //Cuando sueltas tarea al calendario,abre modal para asignar usuario/estado. 
      openUserAssignModal(info.event, { isNew: true });
    },

    eventClick: function(info) {  //si pinchas un evento existente abre modal para editarlo
      openUserAssignModal(info.event, { isNew: false });
    },

    eventDrop: function(info) { //al mover un evento ya creado llama updateEventDate para actualizar fecha via PUT.
      updateEventDate(info.event);
    },

    eventDidMount: function(info) {  //MANEJA COLORES Y APARIENCIA EN FUNCIÓN DEL ESTADO DEL USAURIO. 
      if(info.event.extendedProps.status === 'done') {  //Si la propiedad status está en "donde" pinta en verde el evento
        info.el.style.backgroundColor = '#28a745';  
      } else if(info.event.extendedProps.usuario) {
        info.el.style.backgroundColor = getUserColor(info.event.extendedProps.usuario);
      }
    },

    //detectar cambio de mes y preguntar si duplicar mes anterior
    // NOTA: la comprobación solo se realizará si el usuario ha navegado manualmente
    // con los botones prev/next (se marca con window.__fcNavTriggered).
    datesSet: function(info) {
      // Si no se ha navegado con las flechas (prev/next), no comprobamos nada.
      if (!window.__fcNavTriggered) {
        // Reiniciamos la marca por si viene de otra parte — no hacemos nada.
        window.__fcNavTriggered = false;
        return;
      }

      // Solo procede si el usuario navegó con prev/next -> hacemos la comprobación
      // y luego reseteamos la marca para esperar la siguiente navegación manual.
      window.__fcNavTriggered = false;

      const newYear = info.start.getFullYear();
      const newMonth = info.start.getMonth() + 1;
      const current = new Date(CALENDAR_INITIAL_DATE);
      const currentYear = current.getFullYear();
      const currentMonth = current.getMonth() + 1;

      if (newYear !== currentYear || newMonth !== currentMonth) {
        fetch(`${CALENDAR_HISTORY_EXISTS_URL}?flat_id=${FLAT_ID}&year=${newYear}&month=${newMonth}`, { credentials: 'same-origin' })
          .then(r => {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
          })
          .then(res => {
            if (!res.exists) {
              const prettyMonth = `${String(newMonth).padStart(2,'0')}/${newYear}`;
              if (confirm(`No existe un calendario para ${prettyMonth}. ¿Quieres crear uno copiando la distribución del mes actual?`)) {
                const payload = {
                  source_calendar_id: CALENDAR_ID,
                  target_year: newYear,
                  target_month: newMonth,
                  name: null
                };
                fetch(CALENDAR_CLONE_URL, {
                  method: 'POST',
                  headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF_TOKEN},
                  credentials: 'same-origin',
                  body: JSON.stringify(payload)
                })
                .then(r => r.json())
                .then(res2 => {
                  if (res2.ok && res2.redirect) {
                    window.location.href = res2.redirect;
                  } else {
                    alert(res2.message || 'No se pudo crear el calendario.');
                  }
                })
                .catch(err => {
                  console.error('Error clonando calendario:', err);
                  alert('Error creando el calendario.');
                });
              }
            }
          })
          .catch(err => {
            console.error('Error comprobando existencia de calendario:', err);
          });
      }
    }
  });

  calendar.render();

  // -------------------------
  // Flag + listeners para detectar navegación MANUAL con flechas
  // -------------------------
  // Usamos una variable global simple (window.__fcNavTriggered)
  // que se pone a true cuando el usuario pulsa prev/next (o las flechas del toolbar).
  // datesSet leerá esa marca y solo actuará si estaba a true.
  window.__fcNavTriggered = false;

  // Intentamos enganchar a los botones que FullCalendar crea (.fc-prev-button / .fc-next-button)
  // y también añadimos un handler delegated sobre calendarEl para capturar clicks si los botones
  // se crean dinámicamente o son personalizados.
  try {
    const toolbar = calendarEl.querySelector('.fc-toolbar');
    if (toolbar) {
      const prev = toolbar.querySelector('.fc-prev-button');
      const next = toolbar.querySelector('.fc-next-button');

      if (prev) prev.addEventListener('click', () => { window.__fcNavTriggered = true; });
      if (next) next.addEventListener('click', () => { window.__fcNavTriggered = true; });
    }

    // Delegación adicional: si por alguna razón las flechas están fuera o cambian, detectamos clicks.
    calendarEl.addEventListener('click', (ev) => {
      const t = ev.target;
      if (!t) return;
      if (t.classList && (t.classList.contains('fc-prev-button') || t.classList.contains('fc-next-button'))) {
        window.__fcNavTriggered = true;
      }
    });

    // Opcional: si el usuario usa teclas de flecha para navegar (izq/der), consideramos eso como navegación manual.
    calendarEl.addEventListener('keydown', (ev) => {
      if (ev.key === 'ArrowLeft' || ev.key === 'ArrowRight') {
        window.__fcNavTriggered = true;
      }
    });
  } catch (e) {
    console.warn('No se pudo enganchar listeners de navegación prev/next:', e);
  }

  // --- modal logic ---
  const modal = document.getElementById('userModal');
  const userSelect = document.getElementById('userSelect');
  const statusSelect = document.getElementById('statusSelect');
  let currentEvent = null;
  let currentIsNew = false;

  function openUserAssignModal(event, opts = {}) {
    currentEvent = event;
    currentIsNew = !!opts.isNew;
    userSelect.innerHTML = '';
    FLAT_MEMBERS.forEach(u => {
      let opt = document.createElement('option');
      opt.value = u.id;
      opt.text = u.name;
      userSelect.add(opt);
    });

    if (event.extendedProps && event.extendedProps.usuario) {
      userSelect.value = event.extendedProps.usuario;
    }

    statusSelect.value = event.extendedProps.status || 'pending';
    modal.style.display = 'block';
  }

  document.getElementById('assignBtn').addEventListener('click', function() {
    if(!currentEvent) return;
    let userId = userSelect.value || null;
    let userName = userSelect.options[userSelect.selectedIndex] ? userSelect.options[userSelect.selectedIndex].text : '';
    let status = statusSelect.value || 'pending';

    currentEvent.setExtendedProp('usuario', userId);
    currentEvent.setExtendedProp('status', status);
    const baseTitle = (currentEvent.title || '').split(' - ')[0];
    const newTitle = userName ? `${baseTitle} - ${userName}` : baseTitle;
    currentEvent.setProp('title', newTitle);
    currentEvent.setProp('backgroundColor', status === 'done' ? '#28a745' : getUserColor(userId));

    if (currentIsNew) {

      // --------------------------------------------------------------------
      // ✔ ✔ ✔  BLOQUE SUSTITUIDO — POST NUEVO EVENTO (el que pediste)
      // --------------------------------------------------------------------
      const payload = {
        calendar_id: CALENDAR_ID,
        task_id: currentEvent.extendedProps.taskId,
        assigned_user_id: userId,
        event_date: currentEvent.startStr,
        status: status
      };

      fetch(CALENDAR_EVENTS_BASE_URL, {
        method: 'POST',
        headers: {
          'Content-Type':'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': CSRF_TOKEN
        },
        credentials: 'same-origin',
        body: JSON.stringify(payload)
      })
      .then(async r => {
        const text = await r.text().catch(()=>null);
        let data = null;
        try { data = text ? JSON.parse(text) : null; } catch(e) {}

        if (!r.ok) {
          console.error('Respuesta no OK al crear evento:', r.status, text, data);
          if (r.status === 422 && data && data.errors) {
            console.warn('Errores de validación:', data.errors);
            alert('Errores de validación: ' + JSON.stringify(data.errors));
          } else {
            alert('Error creando evento: servidor respondió ' + r.status + '\nMira la consola para más detalles.');
          }
          return Promise.reject({ status: r.status, body: text, data });
        }

        if (data && data.id) {
          currentEvent.setProp('id', data.id);
        } else {
          console.warn('Respuesta creada pero sin id:', data);
        }
      })
      .catch(err => {
        console.error('Error guardando evento (catch):', err);
        alert('Error guardando el evento en el servidor. Revisa la consola y storage/logs/laravel.log');
      });
      // --------------------------------------------------------------------
      // ✔ ✔ ✔  FIN del bloque nuevo
      // --------------------------------------------------------------------

    } else {
      if (currentEvent.id) {
        fetch(`${CALENDAR_EVENTS_BASE_URL}/${currentEvent.id}`, {
          method: 'PUT',
          headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF_TOKEN},
          credentials: 'same-origin',
          body: JSON.stringify({ assigned_user_id: userId, status: status, event_date: currentEvent.startStr })
        }).then(r => r.json())
          .then(resp => {})
          .catch(err => {
            console.error('Error actualizando evento:', err);
            alert('Error actualizando el evento en servidor.');
          });
      }
    }

    modal.style.display = 'none';
  });

  window.addEventListener('click', function(e) {
    if (e.target === modal) modal.style.display = 'none';
  });

  function updateEventDate(event) {
    if (!event.id) { console.warn('Intentando actualizar evento sin id en servidor.'); return; }
    fetch(`${CALENDAR_EVENTS_BASE_URL}/${event.id}`, {
      method: 'PUT',
      headers: {'Content-Type':'application/json','X-CSRF-TOKEN': CSRF_TOKEN},
      credentials: 'same-origin',
      body: JSON.stringify({ event_date: event.startStr })
    }).then(r => r.json())
      .catch(err => console.error('Error actualizando fecha evento:', err));
  }

  function getUserColor(userId) {
    if (!userId) return '#3788d8';
    const colors = ['#3788d8','#ffc107','#fd7e14','#20c997','#6f42c1','#e83e8c'];
    let idx = Number(userId) || 0;
    return colors[idx % colors.length];
  }

  // --- guardar snapshot ---
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

});
