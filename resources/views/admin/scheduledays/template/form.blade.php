<input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

<div class="form-group">
    <label for="zona_id">Zona a completar:</label>
    <select name="zona_id" id="zona_id" class="form-control" required>
        <option value="">-- Selecciona una zona --</option>
        @foreach ($zonasIncompletas as $zona)
            <option value="{{ $zona->zone_id }}" data-turno="{{ $zona->shift_id }}"
                data-vehiculo="{{ $zona->vehicle_id }}" data-conductor-id="{{ $zona->conductor_id }}"
                data-ayudantes='@json($zona->ayudantes_ids ?? [])'>
                {{ $zona->zone_name }}
            </option>
        @endforeach

    </select>
</div>

<div class="form-group">
    <label for="fechas">Fechas a reemplazar:</label>
    <select name="fechas[]" id="fechas" class="form-control" multiple required>
        @foreach ($incompleteDays as $day)
            <option value="{{ $day->date }}">{{ $day->date }}</option>
        @endforeach
    </select>
    <small class="text-muted">Puedes seleccionar varias fechas manteniendo presionado Ctrl (Cmd en Mac).</small>
</div>

<div class="form-row">
    <div class="form-group col-4">
        <label for="shift_id">Turno:</label>
        <select name="shift_id" id="shift_id" class="form-control" required>
            @foreach ($shifts as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-4">
        <label for="vehicle_id">Vehículo:</label>
        <select name="vehicle_id" id="vehicle_id" class="form-control" required>
            @foreach ($vehicles as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-4">
        <label for="conductor_id">Conductor:</label>
        <select name="conductor_id" id="conductor_id" class="form-control">
            @foreach ($conductores as $id => $nombre)
                <option value="{{ $id }}">{{ $nombre }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label for="ayudantes_ids">Ayudantes:</label>
    {!! Form::select('ayudantes_ids[]', $ayudantes, old('ayudantes_ids'), [
        'class' => 'form-control',
        'id' => 'ayudantes_ids',
        'multiple' => true,
        'required' => true,
    ]) !!}
    <small class="form-text text-muted">
        Mantén presionado Ctrl (Cmd en Mac) para seleccionar varios ayudantes.
    </small>
</div>


<script>
    const zonasAyudantes = @json($zonasAyudantes);

    document.getElementById('zona_id').addEventListener('change', function () {
        const zonaId = this.value;
        const selected = this.options[this.selectedIndex];
        
        const turno = selected.getAttribute('data-turno');
        const vehiculo = selected.getAttribute('data-vehiculo');
        const conductor = selected.getAttribute('data-conductor-id');

        document.getElementById('shift_id').value = turno;
        document.getElementById('vehicle_id').value = vehiculo;
        document.getElementById('conductor_id').value = conductor;

        // Marcar ayudantes preasignados
        const ayudantesPreseleccionados = zonasAyudantes[zonaId] || [];
        const ayudantesSelect = document.getElementById('ayudantes_ids');
        for (let option of ayudantesSelect.options) {
            option.selected = ayudantesPreseleccionados.includes(parseInt(option.value));
        }
    });
</script>

