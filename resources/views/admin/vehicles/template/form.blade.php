<div class="row">
    <div class="col-8">
        <div class="form-row">
            <div class="form-group col-6">
                {!! Form::label('name', 'Nombre') !!}
                {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre del vehículo', 'required']) !!}
            </div>
            <div class="form-group col-6">
                {!! Form::label('code', 'Código') !!}
                {!! Form::text('code', null, ['class' => 'form-control', 'placeholder' => 'Código del vehículo', 'required']) !!}
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                {!! Form::label('brand_id', 'Marca') !!}
                {!! Form::select('brand_id', $brands, null, ['class' => 'form-control', 'id' => 'brand_id', 'required']) !!}
            </div>
            <div class="form-group col-6">
                {!! Form::label('model_id', 'Modelo') !!}
                {!! Form::select('model_id', $models, null, ['class' => 'form-control', 'id' => 'model_id', 'required']) !!}
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                {!! Form::label('type_id', 'Tipo') !!}
                {!! Form::select('type_id', $types, null, ['class' => 'form-control', 'id' => 'type_id', 'required']) !!}
            </div>
            <div class="form-group col-6">
                {!! Form::label('color_id', 'Color') !!}
                {!! Form::select('color_id', $colors, null, ['class' => 'form-control', 'id' => 'color_id', 'required']) !!}
            </div>
        </div>
    </div>

    <div class="col-4 d-flex flex-column align-items-center justify-content-center">
        <div id="imageButton" style="width: 80%; text-align: center; padding: 10px; cursor: pointer;">
            <img src="{{ empty($vehicle->image) ? asset('storage/brand_logo/no_image.png') : asset($vehicle->image) }}"
                alt="" id="imgPreview"
                style="width: 100%; max-width: 200px; height: 200px; object-fit: contain; margin: 0 auto; display: block; cursor: pointer;"
                class="img-fluid">
            <p style="font-size: 12px; text-align:center; margin-top: 10px;">
                Haga click para seleccionar una imagen
            </p>
        </div>
    </div>

</div>
<div class="form-row">
    <div class="form-group col-4">
        {!! Form::label('plate', 'Placa') !!}
        {!! Form::number('plate', null, ['class' => 'form-control', 'placeholder' => 'Placa', 'required']) !!}
    </div>
    <div class="form-group col-4">
        {!! Form::label('year', 'Año') !!}
        {!! Form::number('year', null, ['class' => 'form-control', 'placeholder' => 'Año', 'required']) !!}
    </div>
    <div class="form-group col-4">
        {!! Form::label('load_capacity', 'Capacidad de carga') !!}
        {!! Form::number('load_capacity', null, ['class' => 'form-control', 'placeholder' => 'Carga', 'required']) !!}
    </div>
</div>
<div class="form-row">
    <div class="form-group col-4">
        {!! Form::label('fuel_capacity', 'Capacidad de combustible') !!}
        {!! Form::number('fuel_capacity', null, ['class' => 'form-control', 'placeholder' => 'Combustible', 'required']) !!}
    </div>
    <div class="form-group col-4">
        {!! Form::label('compactation_capacity', 'Compactadas') !!}
        {!! Form::number('compactation_capacity', null, [
            'class' => 'form-control',
            'placeholder' => 'Compactación',
            'required',
        ]) !!}
    </div>
    <div class="form-group col-4">
        {!! Form::label('occupant_capacity', 'Capacidad de ocupantes') !!}
        {!! Form::number('occupant_capacity', 3, ['class' => 'form-control', 'placeholder' => 'Ocupantes', 'required']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, [
        'class' => 'form-control',
        'placeholder' => 'Agregue una descripción',
        'rows' => 3,
    ]) !!}
</div>
<div class="form-check">
    {!! Form::checkbox('status', 1, true, ['class' => 'form-check-input']) !!}
    {!! Form::label('status', 'Estado') !!}
</div>
<div class="form-group">
    {!! Form::file('image', ['accept' => 'image/*', 'id' => 'imgInput', 'class' => 'd-none']) !!}
</div>
<script>
    $('#brand_id').change(function() {
        var id = $(this).val();
        $.ajax({
            url: "{{ route('admin.modelsbybrand', '_id') }}".replace('_id', id),
            type: "GET",
            datatype: "JSON",
            contentype: "application/json",
            success: function(response) {
                $("#model_id").empty();
                $.each(response, function(key, value) {
                    $("#model_id").append("<option value=" + value.id + ">" + value.name +
                        "</option>");
                })
            }
        })
    })

    $('#imgInput').change(function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imgPreview').attr('src', e.target.result).show();
            };
            reader.readAsDataURL(file);
        }
    });
    $('#imageButton').click(function() {
        $('#imgInput').click();
    })
</script>
