<div class="form-group">
    {!! Form::label('shift_id', 'Turno') !!}
    {!! Form::select('shift_id', $shifts, $detalle->shift_id, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('vehicle_id', 'Vehículo') !!}
    {!! Form::select('vehicle_id', $vehicles, $detalle->vehicle_id, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('conductor_id', 'Conductor') !!}
    {!! Form::select('conductor_id', $conductores, $detalle->conductor_id, ['class' => 'form-control', 'required']) !!}
</div>

<div class="form-group">
    {!! Form::label('ayudantes_ids', 'Ayudantes') !!}
    {!! Form::select('ayudantes_ids[]', $ayudantes, $ayudantesAsignados, [
        'class' => 'form-control',
        'multiple' => true,
        'required',
    ]) !!}
</div>
