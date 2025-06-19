<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, [
        'class' => 'form-control',
        'placeholder' => 'Nombre de la ruta',
        'required',
    ]) !!}
</div>

<div class="form-row">
    <div class="form-group col-3">
        {!! Form::label('latitude_start', 'Coordenadas Iniciales') !!}
        {!! Form::text('latitude_start', null, [
            'class' => 'form-control',
            'placeholder' => 'Latitud inicial',
            'required',
            'readonly',
        ]) !!}
    </div>
    <div class="form-group col-3">
        {!! Form::label('longitude_start', '&nbsp;') !!}
        {!! Form::text('longitude_start', null, [
            'class' => 'form-control',
            'placeholder' => 'Longitud inicial',
            'required',
            'readonly',
        ]) !!}
    </div>

    <div class="form-group col-3">
        {!! Form::label('latitude_end', 'Coordenadas Finales') !!}
        {!! Form::text('latitude_end', null, [
            'class' => 'form-control',
            'placeholder' => 'Latitud final',
            'required',
            'readonly',
        ]) !!}
    </div>
    <div class="form-group col-3">
        {!! Form::label('longitude_end', '&nbsp;') !!}
        {!! Form::text('longitude_end', null, [
            'class' => 'form-control',
            'placeholder' => 'Longitud final',
            'required',
            'readonly',
        ]) !!}
    </div>
</div>

<div class="form-check">
    {!! Form::checkbox('status', 1, true, [
        'class' => 'form-check-input',
    ]) !!}
    {!! Form::label('status', 'Estado') !!}
</div>

<div id="map" class="card" style="width: 100%; height: 400px;"></div>

<script>
    var startLatInput = document.getElementById('latitude_start');
    var startLonInput = document.getElementById('longitude_start');
    var endLatInput = document.getElementById('latitude_end');
    var endLonInput = document.getElementById('longitude_end');

    function initMap() {
        var defaultLat = -6.7761; // Coordenada predeterminada
        var defaultLng = -79.8447; // Coordenada predeterminada

        var mapOptions = {
            center: {
                lat: defaultLat,
                lng: defaultLng
            },
            zoom: 15,
        };

        var map = new google.maps.Map(document.getElementById('map'), mapOptions);

        // Marcador verde (Inicio)
        var startMarker = new google.maps.Marker({
            position: {
                lat: defaultLat,
                lng: defaultLng
            },
            map: map,
            title: 'Inicio',
            draggable: true,
            icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png', // Marcador verde
        });

        // Marcador rojo (Fin)
        var endMarker = new google.maps.Marker({
            position: {
                lat: defaultLat + 0.001,
                lng: defaultLng + 0.001
            }, // Un punto cercano
            map: map,
            title: 'Fin',
            draggable: true,
            icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png', // Marcador rojo
        });

        // Actualizar los inputs al mover el marcador de inicio
        google.maps.event.addListener(startMarker, 'dragend', function(event) {
            var latLng = event.latLng;
            startLatInput.value = latLng.lat();
            startLonInput.value = latLng.lng();
        });

        // Actualizar los inputs al mover el marcador de fin
        google.maps.event.addListener(endMarker, 'dragend', function(event) {
            var latLng = event.latLng;
            endLatInput.value = latLng.lat();
            endLonInput.value = latLng.lng();
        });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer>
</script>
