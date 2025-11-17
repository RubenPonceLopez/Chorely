document.addEventListener('DOMContentLoaded', function() {

  // 1️⃣ Hacer tareas arrastrables desde el panel lateral
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

  // 2️⃣ Inicializar calendario
  let calendarEl = document.getElementById('calendar');
  let calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    initialDate: CALENDAR_INITIAL_DATE, // <- NUEVO: usamos la fecha del calendario
    droppable: true,
    editable: true,
    events: CALENDAR_EVENTS_BASE_URL + '/' + CALENDAR_ID,

    eventReceive: function(info) {
      openUserAssignModal(info.event);
    },

    eventClick: function(info) {
      openUserAssignModal(info.event);
    },

    eventDrop: function(info) {
      updateEventDate(info.event);
    },

    eventDidMount: function(info) {
      if(info.event.extendedProps.status === 'done') {
        info.el.style.backgroundColor = '#28a745';
      } else if(info.event.extendedProps.usuario) {
        info.el.style.backgroundColor = getUserColor(info.event.extendedProps.usuario);
      }
    }
  });

  calendar.render();

  // Modal
  const modal = document.getElementById('userModal');
  const userSelect = document.getElementById('userSelect');
  const statusSelect = document.getElementById('statusSelect');
  let currentEvent = null;

  function openUserAssignModal(event) {
    currentEvent = event;
    userSelect.innerHTML = '';
    FLAT_MEMBERS.forEach(u => {
      let opt = document.createElement('option');
      opt.value = u.id;
      opt.text = u.name;
      userSelect.add(opt);
    });
    statusSelect.value = event.extendedProps.status || 'pending';
    modal.style.display = 'block';
  }

  document.getElementById('assignBtn').addEventListener('click', function() {
    if(!currentEvent) return;
    let userId = userSelect.value;
    let userName = userSelect.options[userSelect.selectedIndex].text;
    let status = statusSelect.value;

    currentEvent.setExtendedProp('usuario', userId);
    currentEvent.setExtendedProp('status', status);
    currentEvent.setProp('title', `${currentEvent.title.split(' - ')[0]} - ${userName}`);
    currentEvent.setProp('backgroundColor', status === 'done' ? '#28a745' : getUserColor(userId));

    fetch(CALENDAR_EVENTS_BASE_URL, {
      method: 'POST',
      headers: {'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN},
      body: JSON.stringify({
        task_id: currentEvent.extendedProps.taskId,
        assigned_user_id: userId,
        event_date: currentEvent.startStr,
        status: status
      })
    });

    modal.style.display = 'none';
  });

  function updateEventDate(event) {
    fetch(`${CALENDAR_EVENTS_BASE_URL}/${event.id}`, {
      method: 'PUT',
      headers: {'Content-Type':'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN},
      body: JSON.stringify({ event_date: event.startStr })
    });
  }

  function getUserColor(userId) {
    const colors = ['#3788d8','#ffc107','#fd7e14','#20c997','#6f42c1','#e83e8c'];
    return colors[userId % colors.length];
  }

});
