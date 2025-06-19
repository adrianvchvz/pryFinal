<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => 'Nombre del turno',
        'required',
    ]) !!}
</div>
<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('start_time', 'Hora de inicio') !!}
        {!! Form::time('start_time', null, [
            'class' => 'form-control',
            'required',
        ]) !!}
    </div>
    <div class="form-group col-6">
        {!! Form::label('end_time', 'Hora de fin') !!}
        {!! Form::time('end_time', null, [
            'class' => 'form-control',
            'required',
        ]) !!}
    </div>
</div>
