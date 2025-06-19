{!! Form::model($route, ['route' => ['admin.routes.update', $route], 'method' => 'PUT']) !!}
@include('admin.routes.template.form')
<button type="submit" class="btn btn-success"><i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}

<script>
    var startLatInput = document.getElementById('latitude_start');
    var startLonInput = document.getElementById('longitude_start');
    var endLatInput = document.getElementById('latitude_end');
    var endLonInput = document.getElementById('longitude_end');

    function initMap() {
        // Recuperar las coordenadas del formulario
        var startLat = parseFloat(startLatInput.value) || -6.7761; // Valor predeterminado si no hay coordenadas
        var startLon = parseFloat(startLonInput.value) || -79.8447;
        var endLat = parseFloat(endLatInput.value) || -6.7761;
        var endLon = parseFloat(endLonInput.value) || -79.8447;

        // Configuración del mapa
        var mapOptions = {
            center: {
                lat: startLat,
                lng: startLon
            },
            zoom: 15,
        };

        // Crear el mapa
        var map = new google.maps.Map(document.getElementById('map'), mapOptions);

        // Crear marcador verde (Inicio) en las coordenadas cargadas
        var startMarker = new google.maps.Marker({
            position: {
                lat: startLat,
                lng: startLon
            },
            map: map,
            title: 'Inicio',
            draggable: true,
            icon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png', // Marcador verde
        });

        // Crear marcador rojo (Fin) en las coordenadas cargadas
        var endMarker = new google.maps.Marker({
            position: {
                lat: endLat,
                lng: endLon
            },
            map: map,
            title: 'Fin',
            draggable: true,
            icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png', // Marcador rojo
        });

        // Actualizar las coordenadas de inicio al mover el marcador de inicio
        google.maps.event.addListener(startMarker, 'dragend', function(event) {
            startLatInput.value = event.latLng.lat();
            startLonInput.value = event.latLng.lng();
        });

        // Actualizar las coordenadas de fin al mover el marcador de fin
        google.maps.event.addListener(endMarker, 'dragend', function(event) {
            endLatInput.value = event.latLng.lat();
            endLonInput.value = event.latLng.lng();
        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer>
</script>
