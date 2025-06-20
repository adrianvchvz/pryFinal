<div class='form-row'>
    <div class="form-group col-4">
        {!! Form::label('zona_id', 'Zona') !!}
        {!! Form::select('zona_id', $zones, null, ['class' => 'form-control', 'required']) !!}
    </div>

    <div class="form-group col-4">
        {!! Form::label('vehicle_id', 'Vehículo') !!}
        {!! Form::select('vehicle_id', $vehicles, null, ['class' => 'form-control', 'required']) !!}
    </div>

    <div class="form-group col-4">
        {!! Form::label('shift_id', 'Turno') !!}
        {!! Form::select('shift_id', $shifts, null, ['class' => 'form-control', 'required']) !!}
    </div>
</div>
<div class='form-row'>
    <div class="form-group col-6">
        {!! Form::label('start_date', 'Fecha de inicio') !!}
        {!! Form::date('start_date', null, [
            'class' => 'form-control',
            'required',
        ]) !!}
    </div>
    <div class="form-group col-6">
        {!! Form::label('end_date', 'Fecha de fin') !!}
        {!! Form::date('end_date', null, [
            'class' => 'form-control',
            'required',
        ]) !!}
    </div>
</div>
@php
    $scheduledays = $scheduledays ?? [];
@endphp
<div class="form-group">
    {!! Form::label('days', 'Días de la semana') !!}
    <div>
        @php
            $dias = [
                1 => 'Lunes',
                2 => 'Martes',
                3 => 'Miércoles',
                4 => 'Jueves',
                5 => 'Viernes',
                6 => 'Sábado',
                7 => 'Domingo',
            ];
        @endphp
        @foreach ($dias as $num => $nombre)
            <label class="mr-2">
                {!! Form::checkbox('days[]', $num, isset($scheduledays) && in_array($num, $scheduledays)) !!} {{ $nombre }}
            </label>
        @endforeach
    </div>
</div>

<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('employee_id', 'Conductor') !!}
        {!! Form::select('conductor_id', $conductores, isset($conductor_id) ? $conductor_id : null, [
            'class' => 'form-control',
            'required',
            'id' => 'conductor_id',
        ]) !!}
    </div>
    <div class="form-group col-6">
        {!! Form::label('employee_id', 'Ayudantes') !!}
        {!! Form::select('ayudantes_ids[]', $ayudantes, isset($ayudantes_ids) ? $ayudantes_ids : null, [
            'class' => 'form-control',
            'multiple' => true,
            'required',
            'id' => 'ayudantes_ids',
        ]) !!}
        <small class="form-text text-muted">
            Mantén presionada la tecla <strong>Ctrl</strong> (o <strong>Cmd</strong> en Mac) para seleccionar más de un
            ayudante.
        </small>
    </div>
</div>
<script>
    function formatDate(dateStr) {
        // Si ya está en el formato correcto (YYYY-MM-DD), no lo cambies
        if (!dateStr || dateStr.indexOf('-') === 4) return dateStr;

        // Si viene como DD/MM/YYYY lo convertimos
        if (dateStr.includes('/')) {
            let [d, m, y] = dateStr.split('/');
            return `${y}-${m.padStart(2, '0')}-${d.padStart(2, '0')}`;
        }
        return dateStr; // Devuelve el string tal como está si no se puede formatear
    }

    function cargarEmpleadosDisponibles() {
        let start = formatDate($('#start_date').val());
        let end = formatDate($('#end_date').val());

        // Solo llamar si ambas fechas tienen valor
        if (!start || !end) return;

        // Para conductor
        $.get('employees/available', {
            start_date: start,
            end_date: end,
            type_id: 1
        }, function(data) {
            let $select = $('#conductor_id');
            $select.empty();
            if (data.length) {
                $.each(data, function(_, emp) {
                    $select.append($('<option>', {
                        value: emp.id,
                        text: emp.fullname
                    }));
                });
            } else {
                $select.append('<option value="">No hay conductores disponibles</option>');
            }
        });

        // Para ayudantes
        $.get('employees/available', {
            start_date: start,
            end_date: end,
            type_id: 2
        }, function(data) {
            let $select = $('#ayudantes_ids');
            $select.empty();
            if (data.length) {
                $.each(data, function(_, emp) {
                    $select.append($('<option>', {
                        value: emp.id,
                        text: emp.fullname
                    }));
                });
            } else {
                $select.append('<option value="">No hay ayudantes disponibles</option>');
            }
        });
    }
    $('#start_date, #end_date').on('change', cargarEmpleadosDisponibles);
</script>
