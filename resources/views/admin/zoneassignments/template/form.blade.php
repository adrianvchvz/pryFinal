<div class='form-row'>
    <div class="form-group col-4">
        {!! Form::label('zone_id', 'Zona') !!}
        {!! Form::select('zone_id', $zones, null, ['class' => 'form-control', 'required']) !!}
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
