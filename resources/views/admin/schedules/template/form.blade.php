<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre de la programación', 'required']) !!}
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
