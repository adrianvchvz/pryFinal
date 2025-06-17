<div class="form-row">
    <div class="col-8">
        <div class="form-group">
            {!! Form::label('name', 'Nombre') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre de la marca', 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('description', 'Descripción') !!}
            {!! Form::textarea('description', null, [
                'class' => 'form-control',
                'placeholder' => 'Agregue una descripción',
                'rows' => 4,
            ]) !!}
            <br>
            <div class="form-group">
                {!! Form::file('logo', ['accept' => 'image/*', 'id' => 'imgInput', 'class' => 'd-none']) !!}
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="form-group">
            <div id="imageButton" class="image-button" style="width: 100%; text-align: center; padding: 10px;">
                <img src="{{ empty($brand->logo) ? asset('storage/brand_logo/no_image.png') : asset($brand->logo) }}"
                    alt="" id="imgPreview" style="width: 100%, height: 180px; cursor: pointer;" class="img-fluid" >
                <p style="font-size: 12px">Haga click para seleccionar una imagen</p>
            </div>
        </div>
    </div>
</div>

<script>
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
