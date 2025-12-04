// public/js/calendar-chorely.js
// Versión consolidada y robusta — maneja siempre CALENDAR_ID fijo y la asignación por modal.
// Requiere las siguientes variables desde blade: CSRF_TOKEN, CALENDAR_ID, CALENDAR_INITIAL_DATE, FLAT_ID,
// CALENDAR_EVENTS_BASE_URL, CALENDAR_HISTORY_LIST_URL, CALENDAR_CLONE_FROM_HISTORIAL_URL, CALENDAR_SAVE_HISTORY_URL, FLAT_MEMBERS

document.addEventListener('DOMContentLoaded', function () {

  // ---------- comprobaciones iniciales ----------
  if (typeof CALENDAR_EVENTS_BASE_URL === 'undefined' || typeof CALENDAR_ID === 'undefined') {
    console.error('Variables CALENDAR_EVENTS_BASE_URL o CALENDAR_ID no definidas.'); return;
  }

  // ---------- CSRF + safeFetch ----------
  const __CSRF = (typeof CSRF_TOKEN !== 'undefined') ? CSRF_TOKEN : (document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
  async function safeFetch(url, opts = {}) {
    opts = Object.assign({}, opts);
    if (!('credentials' in opts)) opts.credentials = 'same-origin';
    opts.headers = Object.assign({}, opts.headers || {});
    if (!opts.headers['X-CSRF-TOKEN'] && __CSRF) opts.headers['X-CSRF-TOKEN'] = __CSRF;
    return fetch(url, opts);
  }

  // ---------- util fechas/tiempos ----------
  function dateToYMD(dateObj) {
    if (!dateObj) return '';
    const y = dateObj.getFullYear();
    const m = String(dateObj.getMonth() + 1).padStart(2, '0');
    const d = String(dateObj.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
  }
  function normalizeTime(t) {
    if (t === null || typeof t === 'undefined') return '';
    let s = String(t).trim();
    if (!s) return '';
    if (s.indexOf(':') >= 0) {
      const parts = s.split(':');
      return parts[0].padStart(2,'0') + ':' + (parts[1] || '00').padStart(2,'0');
    }
    return '';
  }
  function timeToMinutes(hhmm) {
    const s = normalizeTime(hhmm);
    if (!s) return null;
    const [h,m] = s.split(':').map(x=>parseInt(x,10));
    return h*60 + (isNaN(m)?0:m);
  }
  function minutesToTime(mins) {
    if (typeof mins !== 'number' || isNaN(mins)) return '';
    const h = Math.floor(mins/60), m = mins%60;
    return String(h).padStart(2,'0')+':'+String(m).padStart(2,'0');
  }
  const DEFAULT_DURATION_MINUTES = 60;
  function normalizeRange(startTime, endTime) {
    const s = normalizeTime(startTime);
    let e = normalizeTime(endTime);
    const sMin = timeToMinutes(s);
    let eMin = timeToMinutes(e);
    if (sMin !== null && (eMin === null || eMin <= sMin)) {
      eMin = sMin + DEFAULT_DURATION_MINUTES;
      e = minutesToTime(eMin);
    }
    return { start: s||'', end: e||'', startMin: sMin, endMin: eMin };
  }
  function rangesOverlap(aStartMin, aEndMin, bStartMin, bEndMin) {
    if (aStartMin===null||aEndMin===null||bStartMin===null||bEndMin===null) return false;
    return (aStartMin < bEndMin && bStartMin < aEndMin);
  }

  // ---------- localStorage (por calendar_id) ----------
  let currentCalendarId = Number(CALENDAR_ID) || null;
  function storageKeyFor(calendarId) { return `chorely_drafts_calendar_${calendarId}`; }
  function loadDraftsFor(calendarId){
    if (!calendarId) return [];
    try{ const raw=localStorage.getItem(storageKeyFor(calendarId)); return raw?JSON.parse(raw):[] }catch(e){console.warn('parse drafts',e); return []}
  }
  function saveDraftsFor(calendarId, d){
    if (!calendarId) return;
    try{ localStorage.setItem(storageKeyFor(calendarId), JSON.stringify(d||[])); }catch(e){ console.error('save drafts',e); }
  }
  function makeTempId(){ return 'temp-'+Date.now().toString(36)+'-'+Math.floor(Math.random()*9000+1000).toString(36); }

  function draftKey(obj) {
    const date = obj.event_date || obj.start || '';
    const start = normalizeTime(obj.start_time || (obj.extendedProps && obj.extendedProps.start_time) || '');
    const taskId = obj.task_id || (obj.extendedProps && (obj.extendedProps.taskId ?? obj.extendedProps.task_id)) || '';
    return `${date}::${start}::${taskId}`;
  }

  // ---------- draggable ----------
  let draggableInstance = null;
  function initDraggable() {
    const tasksEl = document.getElementById('tasks');
    try {
      if (draggableInstance && typeof draggableInstance.destroy === 'function') {
        try { draggableInstance.destroy(); } catch(e) {}
        draggableInstance = null;
      }
    } catch(e){}
    if (!tasksEl) return null;
    try {
      draggableInstance = new FullCalendar.Draggable(tasksEl, {
        itemSelector: '.draggable-item.task',
        eventData: function(eventEl) {
          // IMPORTANT: aseguramos que el data-task-id esté en el elemento (blade lo pone)
          const taskId = eventEl.getAttribute('data-task-id') || eventEl.dataset.taskId || null;
          const title = eventEl.dataset.name || eventEl.textContent.trim();
          return {
            title: title,
            extendedProps: {
              taskId: taskId ? Number(taskId) : null,
              usuario: null,
              status: 'pending',
              start_time: '',
              end_time: ''
            }
          };
        }
      });
      window.__chorely_draggable_instance__ = draggableInstance;
      tasksEl.classList.remove('opacity-60','pointer-events-none');
      return draggableInstance;
    } catch (e) {
      console.warn('Draggable no disponible:', e);
      return null;
    }
  }
  initDraggable();

  // ---------- calendar ----------
  const calendarEl = document.getElementById('calendar');
  if (!calendarEl) { console.error('#calendar no encontrado'); return; }

  function buildEventsUrl(startISO,endISO) {
    const cid = currentCalendarId || CALENDAR_ID;
    return `${CALENDAR_EVENTS_BASE_URL}?calendar_id=${cid}&start=${encodeURIComponent(startISO)}&end=${encodeURIComponent(endISO)}`;
  }

  function mergeServerWithDrafts(serverEvents) {
    const drafts = loadDraftsFor(currentCalendarId);
    const draftKeySet = {};
    drafts.forEach(d => { if (d._deleted) return; draftKeySet[draftKey(d)] = true; });
    const filteredServer = (serverEvents||[]).filter(s => {
      try { const key = draftKey(s); return !draftKeySet[key]; } catch(e){ return true; }
    });
    const merged = filteredServer.concat(drafts.filter(d => !d._deleted));
    return merged;
  }

  function checkConflictFor(dateStr, taskId, userId, startTime, endTime, ignoreEvent) {
    const checkRange = normalizeRange(startTime, endTime);
    if (checkRange.startMin === null || checkRange.endMin === null) return false;
    const evs = calendar.getEvents();
    for (let i=0;i<evs.length;i++){
      const ev = evs[i];
      if (ignoreEvent && ev === ignoreEvent) continue;
      if (ignoreEvent && ignoreEvent.id && ev.id && String(ignoreEvent.id) === String(ev.id)) continue;
      const evDate = ev.start ? dateToYMD(ev.start) : (ev.startStr || '');
      if (evDate !== dateStr) continue;
      const evStart = ev.extendedProps && (ev.extendedProps.start_time || ev.extendedProps.startTime) ? (ev.extendedProps.start_time || ev.extendedProps.startTime) : (ev.start ? ev.start.toTimeString().slice(0,5) : '');
      const evEnd = ev.extendedProps && (ev.extendedProps.end_time || ev.extendedProps.endTime) ? (ev.extendedProps.end_time || ev.extendedProps.endTime) : (ev.end ? ev.end.toTimeString().slice(0,5) : '');
      const evRange = normalizeRange(evStart, evEnd);
      if (evRange.startMin === null || evRange.endMin === null) continue;
      const evTask = ev.extendedProps && (ev.extendedProps.taskId ?? ev.extendedProps.task_id) ? String(ev.extendedProps.taskId ?? ev.extendedProps.task_id) : null;
      if (taskId && evTask && String(taskId)===String(evTask)) {
        if (rangesOverlap(checkRange.startMin, checkRange.endMin, evRange.startMin, evRange.endMin)) {
          return { type:'same_task', conflictingEvent: ev };
        }
      }
      const evUser = ev.extendedProps && (ev.extendedProps.usuario ?? ev.extendedProps.assigned_user_id) ? String(ev.extendedProps.usuario ?? ev.extendedProps.assigned_user_id) : null;
      if (userId && evUser && String(userId)===String(evUser)) {
        if (rangesOverlap(checkRange.startMin, checkRange.endMin, evRange.startMin, evRange.endMin)) {
          return { type:'same_user', conflictingEvent: ev };
        }
      }
    }
    return false;
  }

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    initialDate: CALENDAR_INITIAL_DATE,
    droppable: true,
    editable: true,
    displayEventTime: false,

    events: function(info, successCallback, failureCallback) {
      const url = buildEventsUrl(info.startStr, info.endStr);
      safeFetch(url)
        .then(r=>{ if(!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(serverEvents=>{
          // serverEvents expected as array of calendar events (controller devuelve array)
          const enriched = (serverEvents||[]).map(item => {
            const copy = Object.assign({}, item);
            if (!copy.event_date && copy.start) {
              try { copy.event_date = String(copy.start).split('T')[0]; } catch(e){}
            }
            return copy;
          });
          const merged = mergeServerWithDrafts(enriched);
          const normalized = merged.map(item=>{
            const copy = Object.assign({}, item);
            if (!copy.start && copy.event_date && copy.start_time) copy.start = `${copy.event_date}T${normalizeTime(copy.start_time)}:00`;
            if (!copy.end && copy.event_date && copy.end_time) copy.end = `${copy.event_date}T${normalizeTime(copy.end_time)}:00`;
            copy.extendedProps = copy.extendedProps || {};
            if (copy.start_time) copy.extendedProps.start_time = normalizeTime(copy.start_time);
            if (copy.end_time) copy.extendedProps.end_time = normalizeTime(copy.end_time);
            if (copy.task_id && !copy.extendedProps.taskId) copy.extendedProps.taskId = copy.task_id;
            if (copy.assigned_user_id && !copy.extendedProps.usuario) copy.extendedProps.usuario = copy.assigned_user_id;
            copy.title = copy.title || (copy.extendedProps && copy.extendedProps.taskId ? ('Tarea ' + copy.extendedProps.taskId) : 'Tarea');
            if (copy.extendedProps && copy.extendedProps.color && !copy.backgroundColor) copy.backgroundColor = copy.extendedProps.color;
            return copy;
          });
          successCallback(normalized);
        })
        .catch(err=>{
          console.error('Error cargando eventos (server):', err);
          const drafts = loadDraftsFor(currentCalendarId).filter(d=>!d._deleted);
          successCallback(drafts);
        });
    },

    eventReceive: function(info) {
      if (CALENDAR_IS_LOCKED) { alert('Este mes está cerrado: no se permiten cambios.'); try{ info.revert(); }catch(e){}; return; }
      const ev = info.event;
      ev.setExtendedProp('_temp', true);
      if (!ev.id) {
        const tid = makeTempId();
        try { ev.setProp('id', tid); } catch (e) { ev._def && (ev._def.publicId = tid); }
      }
      ev.setExtendedProp('start_time', '');
      ev.setExtendedProp('end_time', '');
      openUserAssignModal(ev, { isNew: true });
    },

    eventClick: function(info) {
      if (CALENDAR_IS_LOCKED) { alert('Este mes está cerrado: no se permiten cambios.'); return; }
      openUserAssignModal(info.event, { isNew: false });
    },

    eventDrop: function(info) {
      if (CALENDAR_IS_LOCKED) { alert('Este mes está cerrado: no se permiten cambios.'); try{ info.revert(); }catch(e){}; return; }
      const ev = info.event;
      const date = ev.start ? dateToYMD(ev.start) : (ev.startStr || '');
      const startTime = ev.extendedProps && ev.extendedProps.start_time ? normalizeTime(ev.extendedProps.start_time) : (ev.start ? ev.start.toTimeString().slice(0,5) : '');
      const endTime = ev.extendedProps && ev.extendedProps.end_time ? normalizeTime(ev.extendedProps.end_time) : (ev.end ? ev.end.toTimeString().slice(0,5) : '');
      const taskId = ev.extendedProps && (ev.extendedProps.taskId ?? ev.extendedProps.task_id) ? (ev.extendedProps.taskId ?? ev.extendedProps.task_id) : null;
      const userId = ev.extendedProps && (ev.extendedProps.usuario ?? ev.extendedProps.assigned_user_id) ? (ev.extendedProps.usuario ?? ev.extendedProps.assigned_user_id) : null;
      const conflict = checkConflictFor(date, taskId, userId, startTime, endTime, ev);
      if (conflict) {
        if (conflict.type === 'same_task') alert('No se puede mover: otra instancia de la misma tarea ocupa ese horario en ese día.');
        else if (conflict.type === 'same_user') alert('No se puede mover: ese usuario ya tiene una tarea en ese horario en ese día.');
        else alert('No se puede mover: conflicto de horario.');
        try{ info.revert(); }catch(e){console.warn('info.revert() no disponible', e);}
        return;
      }
      persistEventToDrafts(ev);
    },

    eventDidMount: function(info) {
      try {
        const ev = info.event;
        if (ev.extendedProps && ev.extendedProps.status === 'done') {
          info.el.style.backgroundColor = '#28a745';
        } else if (ev.extendedProps && ev.extendedProps.usuario) {
          info.el.style.backgroundColor = getUserColor(ev.extendedProps.usuario);
        } else if (ev.backgroundColor) {
          info.el.style.backgroundColor = ev.backgroundColor;
        } else {
          info.el.style.backgroundColor = '#3788d8';
        }

        const st = ev.extendedProps && ev.extendedProps.start_time ? normalizeTime(ev.extendedProps.start_time) : '';
        const titleEl = info.el.querySelector('.fc-event-title') || info.el.querySelector('.fc-title') || info.el;
        if (titleEl) {
          let txt = titleEl.textContent || '';
          txt = txt.replace(/^\d{1,2}:\d{2}\s+/, '');
          titleEl.textContent = st ? `${st} ${txt}` : txt;
        }
        try{ ev.setAllDay(false); }catch(e){}
      } catch(e){}
    },

    datesSet: function(info) {
      try { calendar.getEvents().forEach(ev => { if (ev.extendedProps && ev.extendedProps._temp && !ev.id) ev.remove(); }); } catch(e){console.warn('datesSet cleanup', e);}
      checkIfCalendarIsLockedForCurrent().catch(()=>{});
      calendar.refetchEvents();
    }
  });

  calendar.render();

  // ---------- bloqueo por 'Definitivo' ----------
  let CALENDAR_IS_LOCKED = false;

  async function checkIfCalendarIsLockedForCurrent() {
    try {
      if (!currentCalendarId) { applyLock(false); return false; }
      const url = `${CALENDAR_HISTORY_LIST_URL}?flat_id=${FLAT_ID}`;
      const res = await safeFetch(url);
      if (!res.ok) throw new Error('HTTP ' + res.status);
      const json = await res.json();
      const snaps = json && Array.isArray(json.snapshots) ? json.snapshots : [];
      const viewStart = calendar.view.currentStart;
      const year = viewStart.getFullYear();
      const month = viewStart.getMonth()+1;
      const found = snaps.find(s => Number(s.calendar_id) === Number(currentCalendarId) && Number(s.year)===year && Number(s.month)===month && s.versiones==='Definitivo');
      const locked = !!found;
      applyLock(locked);
      return locked;
    } catch (e) {
      console.warn('[checkIfCalendarIsLockedForCurrent] error:', e);
      applyLock(false);
      return false;
    }
  }

  function applyLock(locked) {
    CALENDAR_IS_LOCKED = !!locked;
    try { calendar.setOption('editable', !locked); calendar.setOption('droppable', !locked); } catch(e){}
    try {
      const tasksEl = document.getElementById('tasks');
      if (locked) {
        try { if (draggableInstance && typeof draggableInstance.destroy === 'function') { draggableInstance.destroy(); draggableInstance = null; window.__chorely_draggable_instance__ = null; } } catch(e){}
        if (tasksEl) tasksEl.classList.add('opacity-60','pointer-events-none');
      } else {
        if (!draggableInstance) initDraggable();
        if (tasksEl) tasksEl.classList.remove('opacity-60','pointer-events-none');
      }
    } catch(e){}
    const saveBtn = document.getElementById('saveDistributionBtn');
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    if (saveBtn) saveBtn.disabled = locked;
    if (saveDraftBtn) saveDraftBtn.disabled = locked;
    if (locked) console.info('Este calendario está marcado como DEFINITIVO: edición desactivada.');
  }

  checkIfCalendarIsLockedForCurrent().catch(()=>{});

  // ---------- modal + handlers ----------
  const modal = document.getElementById('userModal');
  const modalBackdrop = document.getElementById('userModalBackdrop');
  const userSelect = document.getElementById('userSelect');
  const statusSelect = document.getElementById('statusSelect');
  const startTimeInput = document.getElementById('startTimeInput');
  const endTimeInput = document.getElementById('endTimeInput');
  const assignBtn = document.getElementById('assignBtn');
  const cancelBtn = document.getElementById('cancelAssignBtn');

  let currentEvent = null;
  let currentIsNew = false;
  let prevSnapshot = null;

  function snapshotEventState(ev) {
    return {
      id: ev.id || null,
      title: ev.title || '',
      startISO: ev.start ? ev.start.toISOString() : null,
      endISO: ev.end ? ev.end.toISOString() : null,
      extendedProps: Object.assign({}, ev.extendedProps || {})
    };
  }
  function restoreEventState(ev, snap) {
    try {
      if (!snap) return;
      ev.setProp('title', snap.title || '');
      if (snap.extendedProps) Object.keys(snap.extendedProps).forEach(k=>ev.setExtendedProp(k, snap.extendedProps[k]));
      if (snap.startISO) ev.setStart(snap.startISO);
      if (snap.endISO) ev.setEnd(snap.endISO);
    } catch(e){ console.warn('restoreEventState', e); }
  }

  function openUserAssignModal(event, opts) {
    currentEvent = event;
    currentIsNew = !!(opts && opts.isNew);
    prevSnapshot = snapshotEventState(event);

    if (userSelect) {
      userSelect.innerHTML = '';
      if (Array.isArray(FLAT_MEMBERS)) {
        FLAT_MEMBERS.forEach(u => {
          const o = document.createElement('option');
          o.value = u.id;
          o.text = u.name;
          userSelect.add(o);
        });
      }
      userSelect.value = event.extendedProps && (event.extendedProps.usuario ?? event.extendedProps.assigned_user_id) ? (event.extendedProps.usuario ?? event.extendedProps.assigned_user_id) : '';
    }
    if (statusSelect) statusSelect.value = event.extendedProps && event.extendedProps.status ? event.extendedProps.status : 'pending';
    const st = event.extendedProps && event.extendedProps.start_time ? normalizeTime(event.extendedProps.start_time) : '';
    const et = event.extendedProps && event.extendedProps.end_time ? normalizeTime(event.extendedProps.end_time) : '';
    if (startTimeInput) startTimeInput.value = st;
    if (endTimeInput) endTimeInput.value = et;

    if (modalBackdrop) modalBackdrop.style.display = 'block';
    if (modal) modal.style.display = 'block';
  }

  function persistEventToDrafts(ev) {
    if (!currentCalendarId) { console.warn('No currentCalendarId al persistir draft — abortando'); return; }
    const drafts = loadDraftsFor(currentCalendarId);
    const date = ev.start ? dateToYMD(ev.start) : (ev.startStr || '');
    const draft = {
      id: ev.id || null,
      calendar_id: currentCalendarId,
      task_id: ev.extendedProps && (ev.extendedProps.taskId ?? ev.extendedProps.task_id) ? (ev.extendedProps.taskId ?? ev.extendedProps.task_id) : null,
      assigned_user_id: ev.extendedProps && (ev.extendedProps.usuario ?? ev.extendedProps.assigned_user_id) ? (ev.extendedProps.usuario ?? ev.extendedProps.assigned_user_id) : null,
      event_date: date,
      start_time: ev.extendedProps && ev.extendedProps.start_time ? normalizeTime(ev.extendedProps.start_time) : '',
      end_time: ev.extendedProps && ev.extendedProps.end_time ? normalizeTime(ev.extendedProps.end_time) : '',
      status: ev.extendedProps && ev.extendedProps.status ? ev.extendedProps.status : 'pending',
      title: ev.title || ''
    };
    const key = draftKey(draft);
    const idx = drafts.findIndex(d=>draftKey(d)===key);
    if (idx>=0) drafts[idx] = draft;
    else { if (!draft.id) draft.id = makeTempId(); drafts.push(draft); }

    try {
      if (ev.id !== draft.id) {
        try { ev.setProp('id', draft.id); } catch(e){ ev._def && (ev._def.publicId = draft.id); }
      }
    } catch(e) {}
    saveDraftsFor(currentCalendarId, drafts);
  }

  function deleteDraftForEvent(ev){
    if (!currentCalendarId) return;
    const drafts = loadDraftsFor(currentCalendarId);
    const date = ev.start ? dateToYMD(ev.start) : (ev.startStr || '');
    const key = `${date}::${normalizeTime(ev.extendedProps && ev.extendedProps.start_time ? ev.extendedProps.start_time : (ev.start ? ev.start.toTimeString().slice(0,5) : ''))}::${ev.extendedProps && (ev.extendedProps.taskId ?? ev.extendedProps.task_id) ? (ev.extendedProps.taskId ?? ev.extendedProps.task_id) : ''}`;
    const idx = drafts.findIndex(d=>draftKey(d)===key);
    if (idx>=0) {
      const d = drafts[idx];
      if (d.id && String(d.id).indexOf('temp-')!==0) { d._deleted = true; drafts[idx]=d; }
      else drafts.splice(idx,1);
      saveDraftsFor(currentCalendarId, drafts);
    }
  }

  // ---------- assign modal handlers (robusto) ----------
  if (assignBtn) {
    assignBtn.addEventListener('click', async function(){
      // Recuperar evento si currentEvent nulo
      if (!currentEvent) {
        console.warn('[assign] currentEvent null — intentando recuperar...');
        let recovered = null;
        if (prevSnapshot && prevSnapshot.id) {
          recovered = calendar.getEventById(prevSnapshot.id) || null;
        }
        if (!recovered) {
          const evs = calendar.getEvents();
          for (let i = 0; i < evs.length; i++) {
            const e = evs[i];
            if (e.extendedProps && e.extendedProps._temp) { recovered = e; break; }
          }
        }
        if (!recovered) {
          alert('No se pudo identificar el evento a asignar. Vuelve a arrastrar la tarea o recarga la página.');
          if (modal) modal.style.display = 'none';
          if (modalBackdrop) modalBackdrop.style.display = 'none';
          return;
        }
        currentEvent = recovered;
        console.info('[assign] evento recuperado:', currentEvent.id);
      }

      if (CALENDAR_IS_LOCKED) { alert('Este mes está cerrado: no se permiten cambios.'); return; }

      const userId = userSelect ? (userSelect.value || null) : null;
      const userName = (userSelect && (userSelect.options[userSelect.selectedIndex]||{}).text) || '';
      const status = statusSelect ? (statusSelect.value || 'pending') : 'pending';
      const startTime = startTimeInput ? normalizeTime(startTimeInput.value) : '';
      const endTime = endTimeInput ? normalizeTime(endTimeInput.value) : '';

      if (startTime && endTime && startTime >= endTime) { alert('La hora de inicio debe ser anterior a la hora de fin.'); return; }

      const taskId = currentEvent.extendedProps && (currentEvent.extendedProps.taskId ?? currentEvent.extendedProps.task_id) ? (currentEvent.extendedProps.taskId ?? currentEvent.extendedProps.task_id) : null;
      const eventDate = currentEvent.start ? dateToYMD(currentEvent.start) : (currentEvent.startStr || '');
      if (eventDate && (taskId || userId)) {
        const conflict = checkConflictFor(eventDate, taskId, userId, startTime, endTime, currentEvent);
        if (conflict) {
          if (conflict.type === 'same_task') alert('No se puede asignar: ya existe la misma tarea en ese día con horario solapado.');
          else if (conflict.type === 'same_user') alert('No se puede asignar: ese usuario ya tiene otra tarea en ese horario del mismo día.');
          else alert('No se puede asignar por conflicto de horario.');
          return;
        }
      }

      try {
        if (typeof currentEvent.setExtendedProp === 'function') {
          currentEvent.setExtendedProp('usuario', userId);
          currentEvent.setExtendedProp('status', status);
          currentEvent.setExtendedProp('start_time', startTime);
          currentEvent.setExtendedProp('end_time', endTime);
        } else {
          currentEvent.extendedProps = currentEvent.extendedProps || {};
          currentEvent.extendedProps.usuario = userId;
          currentEvent.extendedProps.status = status;
          currentEvent.extendedProps.start_time = startTime;
          currentEvent.extendedProps.end_time = endTime;
        }
      } catch(e) { console.error('Error setExtendedProp:', e); }

      try {
        if (startTime) currentEvent.setStart(`${eventDate}T${startTime}:00`);
        if (endTime) currentEvent.setEnd(`${eventDate}T${endTime}:00`);
        else try{ currentEvent.setEnd(null); }catch(e){}
      } catch(e){ console.warn('setStart/setEnd', e); }

      const rawTitle = (currentEvent.title || '').replace(/^\d{1,2}:\d{2}\s+/, '');
      const baseTitle = rawTitle.split(' - ')[0];
      const newTitle = (startTime ? (startTime + ' ') : '') + baseTitle + (userName ? (' - ' + userName) : '');
      try { currentEvent.setProp('title', newTitle); } catch(e){ console.warn('setProp title', e); }

      try { currentEvent.setProp('backgroundColor', status === 'done' ? '#28a745' : getUserColor(userId)); } catch(e){}

      try{ currentEvent.setAllDay(false); }catch(e){}

      try {
        persistEventToDrafts(currentEvent);
      } catch(e) { console.error('persistEventToDrafts error:', e); }

      if (modal) modal.style.display = 'none';
      if (modalBackdrop) modalBackdrop.style.display = 'none';
      prevSnapshot = null; currentEvent = null; currentIsNew = false;
    });
  }

  if (cancelBtn) {
    cancelBtn.addEventListener('click', function(){
      if (!currentEvent) {
        if (modal) modal.style.display = 'none';
        if (modalBackdrop) modalBackdrop.style.display = 'none';
        return;
      }
      if (currentIsNew) {
        try{ currentEvent.remove(); }catch(e){}
      } else {
        if (prevSnapshot) restoreEventState(currentEvent, prevSnapshot);
      }
      if (modal) modal.style.display = 'none';
      if (modalBackdrop) modalBackdrop.style.display = 'none';
      prevSnapshot = null; currentEvent = null; currentIsNew = false;
    });
  }
  if (modalBackdrop) modalBackdrop.addEventListener('click', function(){ if (cancelBtn) cancelBtn.click(); });

  // ---------- Save distribution (Definitivo) ----------
  const saveBtn = document.getElementById('saveDistributionBtn');
  if (saveBtn) {
    saveBtn.addEventListener('click', async function(){
      if (!confirm('¿Quieres guardar la distribución actual de este mes en el historial como DEFINITIVO? (tras esto no podrás mover o añadir tareas en esa snapshot)')) return;
      if (!currentCalendarId) { alert('Calendar id indefinido. Recarga la página.'); return; }
      saveBtn.disabled = true; saveBtn.textContent = 'Guardando...';
      const drafts = loadDraftsFor(currentCalendarId).filter(d=>!d._deleted);
      const viewStart = calendar.view.currentStart;
      const year = viewStart.getFullYear();
      const month = viewStart.getMonth()+1;
      const payload = { calendar_id: currentCalendarId, flat_id: FLAT_ID, year, month, versiones: 'Definitivo', events: drafts };
      try {
        const r = await safeFetch(CALENDAR_SAVE_HISTORY_URL, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        let json = null;
        try{ json = await r.json(); }catch(e){ json = null; }
        if (!r.ok) { console.error('Error guardando distribución (DEF):', r.status, json); alert('Error guardando (ver consola)'); }
        else {
          alert((json && json.message) ? json.message : 'Distribución guardada como DEFINITIVO.');
          saveDraftsFor(currentCalendarId, []);
          setTimeout(()=> window.location.reload(), 350);
        }
      } catch(e) {
        console.error('Error save distribution (DEF)', e); alert('Error guardando (ver consola)');
      } finally { saveBtn.disabled = false; saveBtn.textContent = 'Guardar esta distribución'; }
    });
  }

  // ---------- Save draft ----------
  const saveDraftBtn = document.getElementById('saveDraftBtn');
  if (saveDraftBtn) {
    saveDraftBtn.addEventListener('click', async function(){
      if (!confirm('¿Guardar la distribución actual como BORRADOR? (podrás seguir editándola posteriormente)')) return;
      if (!currentCalendarId) { alert('Calendar id indefinido. Recarga la página.'); return; }
      saveDraftBtn.disabled = true; saveDraftBtn.textContent = 'Guardando...';
      const drafts = loadDraftsFor(currentCalendarId).filter(d=>!d._deleted);
      const viewStart = calendar.view.currentStart;
      const year = viewStart.getFullYear();
      const month = viewStart.getMonth()+1;
      const payload = { calendar_id: currentCalendarId, flat_id: FLAT_ID, year, month, versiones: 'Borrador', events: drafts };
      try {
        const r = await safeFetch(CALENDAR_SAVE_HISTORY_URL, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        let json = null;
        try{ json = await r.json() }catch(e){ json = null; }
        if (!r.ok) { console.error('Error guardando BORRADOR:', r.status, json); alert('Error guardando (ver consola)'); }
        else {
          alert((json && json.message) ? json.message : 'Distribución guardada como BORRADOR.');
        }
      } catch(e) {
        console.error('Error save draft', e); alert('Error guardando (ver consola)');
      } finally { saveDraftBtn.disabled = false; saveDraftBtn.textContent = 'Guardar borrador'; }
    });
  }

  // ---------- modal duplicar ----------
  (function setupDuplicateModal(){
    const duplicateModal = document.getElementById('duplicateModal');
    const openDuplicateModalBtn = document.getElementById('openDuplicateModalBtn');
    const closeDuplicateModal = document.getElementById('closeDuplicateModal');
    const snapshotSelect = document.getElementById('snapshotSelect');
    const cloneYearInput = document.getElementById('cloneYear');
    const cloneMonthInput = document.getElementById('cloneMonth');
    const cloneBtn = document.getElementById('cloneFromSnapshotBtn');

    if (!openDuplicateModalBtn || !snapshotSelect || !cloneBtn || !closeDuplicateModal) return;

    function setCloneButtonEnabled(enabled) {
      if (enabled) {
        cloneBtn.disabled = false;
        cloneBtn.classList.remove('opacity-50','cursor-not-allowed');
        cloneBtn.style.backgroundColor = '#2563eb';
        cloneBtn.style.color = '#ffffff';
      } else {
        cloneBtn.disabled = true;
        cloneBtn.classList.add('opacity-50','cursor-not-allowed');
        cloneBtn.style.backgroundColor = '#2563eb';
        cloneBtn.style.color = '#ffffff';
      }
    }

    openDuplicateModalBtn.addEventListener('click', ()=> {
      duplicateModal.style.display = 'block';
      snapshotSelect.innerHTML = '<option value="">Cargando...</option>';
      setCloneButtonEnabled(false);

      safeFetch(`${CALENDAR_HISTORY_LIST_URL}?flat_id=${FLAT_ID}`)
        .then(r=>{ if(!r.ok) throw new Error('HTTP '+r.status); return r.json(); })
        .then(json=>{
          const snaps = json && Array.isArray(json.snapshots) ? json.snapshots : [];
          snapshotSelect.innerHTML = '';
          if (!snaps || snaps.length===0) {
            const opt = document.createElement('option'); opt.value=''; opt.textContent='No hay meses definitivos'; snapshotSelect.appendChild(opt);
            setCloneButtonEnabled(false);
            return;
          }
          const placeholder = document.createElement('option'); placeholder.value=''; placeholder.textContent='Selecciona mes guardado...'; snapshotSelect.appendChild(placeholder);
          snaps.forEach(s=>{
            const o = document.createElement('option');
            o.value = s.id;
            o.textContent = `${String(s.month).padStart(2,'0')}/${s.year} — guardado: ${new Date(s.created_at).toLocaleString()}`;
            o.dataset.calendarId = s.calendar_id ?? '';
            snapshotSelect.appendChild(o);
          });
          setCloneButtonEnabled(true);
        })
        .catch(err=>{
          console.error('Error listing snapshots (dup):', err);
          snapshotSelect.innerHTML = '<option value="">Error cargando snapshots</option>';
          setCloneButtonEnabled(false);
        });
    });

    closeDuplicateModal.addEventListener('click', ()=> duplicateModal.style.display='none');

    cloneBtn.addEventListener('click', async ()=> {
      const historialId = snapshotSelect.value;
      const targetYear = parseInt(cloneYearInput.value,10);
      const targetMonth = parseInt(cloneMonthInput.value,10);
      if (!historialId) { alert('Selecciona un mes guardado'); return; }
      if (!targetYear || !targetMonth || targetMonth<1 || targetMonth>12) { alert('Introduce un año/mes destino válidos'); return; }
      cloneBtn.disabled = true; cloneBtn.textContent = 'Duplicando...';
      try {
        const r = await safeFetch(CALENDAR_CLONE_FROM_HISTORIAL_URL, {
          method: 'POST',
          headers: {'Content-Type':'application/json'},
          body: JSON.stringify({ historial_id: historialId, target_year: targetYear, target_month: targetMonth, target_calendar_id: currentCalendarId })
        });
        let json = null;
        try{ json = await r.json(); }catch(e){ json = null; }
        if (!r.ok) {
          console.error('Error clonando:', r.status, json);
          alert(json && json.message ? json.message : 'Error clonando (ver consola)');
        } else {
          setTimeout(()=> window.location.reload(), 350);
        }
      } catch(e){
        console.error('Clone error:', e); alert('Error interno al clonar (ver consola)');
      } finally {
        cloneBtn.disabled = false; cloneBtn.textContent = 'Duplicar';
      }
    });

  })();

  // ---------- helpers ----------
  function getUserColor(userId) {
    if (!userId) return '#3788d8';
    const colors = ['#3788d8','#ffc107','#fd7e14','#20c997','#6f42c1','#e83e8c'];
    const idx = Number(userId) || 0;
    return colors[idx % colors.length];
  }

}); // DOMContentLoaded
