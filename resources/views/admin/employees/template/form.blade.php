<div class="row">
    <div class="col-8">
        <div class="form-row">
            <div class="form-group col-4">
                {!! Form::label('dni', 'Documento') !!}
                {!! Form::number('dni', null, [
                    'class' => 'form-control',
                    'placeholder' => 'DNI',
                    'required',
                    'min' => '0',
                    'max' => '99999999',
                    'maxlength' => '8',
                    'oninput' => "if(this.value.length > 8) this.value = this.value.slice(0,8);"
                ]) !!}
            </div>
            <div class="form-group col-4">
                {!! Form::label('lastnames', 'Apellidos') !!}
                {!! Form::text('lastnames', null, ['class' => 'form-control', 'placeholder' => 'Apellidos', 'required']) !!}
            </div>
            <div class="form-group col-4">
                {!! Form::label('names', 'Nombres') !!}
                {!! Form::text('names', null, ['class' => 'form-control', 'placeholder' => 'Nombres', 'required']) !!}
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-4">
                {!! Form::label('type_id', 'Tipo de empleado') !!}
                {!! Form::select('type_id', $types, null, ['class' => 'form-control', 'required']) !!}
            </div>
            <div class="form-group col-4">
                {!! Form::label('password', 'Contraseña') !!}
                <div class="input-group">
                    {!! Form::password('password', [
                        'class' => 'form-control',
                        'placeholder' => 'Contraseña',
                        'id' => 'password-input',
                        isset($employee) ? null : 'required'
                    ]) !!}
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="toggle-password" tabindex="-1">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="form-group col-4">
                {!! Form::label('phone', 'Celular') !!}
                {!! Form::text('phone', null, [
                    'class' => 'form-control',
                    'placeholder' => 'Celular',
                    'maxlength' => '9',
                    'pattern' => '[0-9]{9}',
                    'oninput' => "this.value = this.value.replace(/[^0-9]/g, '').slice(0,9);",
                ]) !!}

            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-6">
                {!! Form::label('birthdate', 'Fecha de nacimiento') !!}
                {!! Form::date('birthdate', null, ['class' => 'form-control', 'required']) !!}
            </div>
            <div class="form-group col-6">
                {!! Form::label('license', 'Licencia') !!}
                {!! Form::text('license', null, ['class' => 'form-control', 'placeholder' => 'Licencia']) !!}
            </div>
        </div>
    </div>

    <div class="col-4 d-flex flex-column align-items-center justify-content-center">
        <div id="imageButton" style="width: 80%; text-align: center; padding: 10px; cursor: pointer;">
            <img src="{{ empty($employee->photo) ? asset('storage/brand_logo/no_image.png') : asset($employee->photo) }}"
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
    <div class="form-group col-6">
        {!! Form::label('address', 'Dirección') !!}
        {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Dirección', 'required']) !!}
    </div>
    <div class="form-group col-6">
        {!! Form::label('email', 'Correo electrónico') !!}
        {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Correo electrónico']) !!}
    </div>
</div>
<div class="form-check">
    {!! Form::checkbox('status', 1, true, ['class' => 'form-check-input']) !!}
    {!! Form::label('status', 'Estado') !!}
</div>
<div class="form-group">
    {!! Form::file('photo', ['accept' => 'image/*', 'id' => 'imgInput', 'class' => 'd-none']) !!}
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
    $(document).on('click', '#toggle-password', function() {
        var input = $('#password-input');
        var icon = $('#password-icon');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
</script>
