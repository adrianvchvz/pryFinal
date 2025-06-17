<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => 'Nombre del color',
        'required'
    ]) !!}
</div>
<div class="form-group">
    {!! Form::label('colorPicker', 'Elegir color') !!}
    <input type="color" id="colorPicker" class="form-control" style="width: 60px; padding: 2px;">
</div>
<div class="form-group">
    {!! Form::label('code', 'Código RGB') !!}
    {!! Form::text('code', null, [
        'class' => 'form-control',
        'placeholder' => 'Código RGB',
        'id' => 'code',
        'readonly',
        'required',
    ]) !!}
</div>
<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, [
        'class' => 'form-control',
        'placeholder' => 'Agregue una descripción',
        'rows' => 4,
    ]) !!}
</div>

<script>
    function hexToRgb(hex) {
        hex = hex.replace('#', '');
        var bigint = parseInt(hex, 16);
        var r = (bigint >> 16) & 255;
        var g = (bigint >> 8) & 255;
        var b = bigint & 255;
        return 'rgb(' + r + ',' + g + ',' + b + ')';
    }

    $('#colorPicker').on('input', function() {
        var hex = $(this).val();
        var rgb = hexToRgb(hex);
        $('#code').val(rgb);
    });

    // Al cargar el form (para editar), poner el color en el picker si es rgb
    $(document).ready(function() {
        if ($('#code').val().startsWith('rgb')) {
            var rgb = $('#code').val();
            var arr = rgb.match(/\d+/g);
            if (arr && arr.length === 3) {
                var hex = "#" + ((1 << 24) + (parseInt(arr[0]) << 16) + (parseInt(arr[1]) << 8) + parseInt(arr[
                    2])).toString(16).slice(1);
                $('#colorPicker').val(hex);
            }
        }
    });
</script>
