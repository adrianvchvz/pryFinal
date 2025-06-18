<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre de la zona', 'required']) !!}
</div>
<div class="form-row">
    <div class="form-group col-4">
        {!! Form::label('area', 'Área') !!}
        {!! Form::number('area', null, ['class' => 'form-control', 'placeholder' => 'Área', 'required']) !!}
    </div>
    <div class="form-group col-8">
        {!! Form::label('district_id', 'Distrito') !!}
        {!! Form::select('district_id', $districts, null, ['class' => 'form-control', 'required']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, [
        'class' => 'form-control',
        'placeholder' => 'Agregue una descripción',
        'rows' => 5,
    ]) !!}
</div>
