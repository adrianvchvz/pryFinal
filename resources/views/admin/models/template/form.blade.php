<div class="form-group">
    {!! Form::label('brand_id', 'Marca') !!}
    {!! Form::select('brand_id', $brands, null, ['class' => 'form-control', 'required']) !!}
</div>
<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre del modelo', 'required']) !!}
</div>
<div class="form-group">
    {!! Form::label('code', 'Código') !!}
    {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => 'Código del modelo', 'required']) !!}
</div>
<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, [
        'class' => 'form-control',
        'placeholder' => 'Agregue una descripción',
        'rows' => 4,
    ]) !!}
</div>