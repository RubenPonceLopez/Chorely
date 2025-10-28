{{-- resources/views/calendars/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6">
  <div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Crear nuevo calendario</h2>
    <form id="createCalendarForm">
      <div class="mb-3">
        <label class="block text-sm">Nombre</label>
        <input name="name" id="name" class="w-full p-2 border rounded" required>
      </div>

      <div class="mb-3 grid grid-cols-2 gap-2">
        <div>
          <label class="block text-sm">Mes inicio</label>
          <input type="month" name="month_start" id="month_start" class="w-full p-2 border rounded" required>
        </div>
        <div>
          <label class="block text-sm">Piso</label>
          <select name="flat_id" id="flat_id" class="w-full p-2 border rounded">
            @foreach($flats as $flat)
              <option value="{{ $flat->id }}">{{ $flat->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div id="participants" class="mb-3">
        <label class="block text-sm">Participantes (IDs)</label>
        <div class="flex gap-2">
          <input name="participants[]" class="flex-1 p-2 border rounded" placeholder="user id (ej: 1)">
          <button type="button" id="addParticipant" class="px-3 bg-indigo-600 text-white rounded">+</button>
        </div>
      </div>

      <div>
        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Crear calendario</button>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('addParticipant').addEventListener('click', () => {
  const container = document.getElementById('participants');
  const div = document.createElement('div');
  div.className = 'flex gap-2 mt-2';
  div.innerHTML = `<input name="participants[]" class="flex-1 p-2 border rounded" placeholder="user id">
                   <button type="button" class="removeBtn px-3 bg-red-500 text-white rounded">-</button>`;
  container.appendChild(div);
  div.querySelector('.removeBtn').addEventListener('click', ()=> div.remove());
});

document.getElementById('createCalendarForm').addEventListener('submit', async (e)=> {
  e.preventDefault();
  const form = e.target;
  const payload = Object.fromEntries(new FormData(form).entries());
  // participants array hack:
  const participants = Array.from(form.querySelectorAll('input[name="participants[]"]')).map(i=>i.value).filter(Boolean);
  payload.participants = participants;

  const res = await fetch('{{ route("calendars.store") }}', {
    method: 'POST',
    headers: {'Content-Type':'application/json','X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
    body: JSON.stringify(payload)
  });
  const data = await res.json();
  if (data.ok) {
    window.location.href = data.redirect;
  } else {
    alert('Error al crear calendario');
  }
});
</script>
@endsection
