@extends('adminlte::page')

@section('title', 'Rutas')

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <h3>Rutas por zonas</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            <label>
                                <span class="font-weight-bold">Ruta: </span> <span
                                    class="font-weight-normal">{{ $route->name }}</span>
                            </label>
                            <br>
                            <label>
                                <span class="font-weight-bold">Latitud y Longitud Inicial: </span> <span
                                    class="font-weight-normal"> ({{ $route->latitude_start }},
                                    {{ $route->longitude_start }})</span>
                            </label>
                            <br>
                            <label>
                                <span class="font-weight-bold">Latitud y Longitud Final: </span> <span
                                    class="font-weight-normal"> ({{ $route->latitude_end }},
                                    {{ $route->longitude_end }})</span>
                            </label>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-primary float-right" id="btnNuevo" data-id={{ $route->id }}><i
                                    class="fas fa-plus"></i></button>
                            <h4>Lista de Zonas</h4>
                        </div>
                        <div class="card-body">
                            <table class="display" id="datatable">
                                <thead>
                                    <tr>
                                        <th>Zonas</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">Visualización del trayecto de la ruta</div>
                        <div class="card-body">
                            <div id="map" style="height:400px"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.routes.index') }}" class="btn btn-danger float-right">
                <i></i>Retornar</a>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLongTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                },
                "ajax": "{{ route('admin.routezones.show', $route->id) }}",
                "columns": [{
                        "data": "zone_name",
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        "data": "delete",
                        "orderable": false,
                        "searchable": false
                    }
                ]
            });
        })

        $('#btnNuevo').click(function() {
            var id = $(this).attr('data-id');

            $.ajax({
                url: "{{ route('admin.routezones.create', '_id') }}".replace('_id', id),
                type: "GET",
                success: function(response) {
                    $('.modal-title').html("Nueva coordenada");
                    $('#ModalCenter .modal-body').html(response);
                    $('#ModalCenter').modal('show');

                    $('#ModalCenter form').on('submit', function(e) {
                        e.preventDefault();
                        var form = $(this);
                        var formdata = new FormData(this);
                        $.ajax({
                            url: form.attr('action'),
                            type: form.attr('method'),
                            data: formdata,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                $('#ModalCenter').modal('hide');
                                refreshTable();
                                Swal.fire({
                                    title: "Proceso exitoso",
                                    icon: "success",
                                    text: response.message,
                                    draggable: true
                                });
                            },
                            error: function(xhr) {
                                var response = xhr.responseJSON;
                                Swal.fire({
                                    title: "Error",
                                    icon: "error",
                                    text: response.message,
                                    draggable: true
                                });
                            }
                        })
                    })
                }
            })
        })

        $(document).on('submit', '.frmDelete', function(e) {
            e.preventDefault();
            var form = $(this);
            Swal.fire({
                title: "Está seguro de eliminar?",
                text: "Este proceso no es reversible!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar!"
            }).then((result) => {
                if (result.isConfirmed) {
                    //this.submit();
                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        data: form.serialize(),
                        success: function(response) {
                            refreshTable();
                            Swal.fire({
                                title: "Proceso exitoso",
                                icon: "success",
                                text: response.message,
                                draggable: true
                            });
                        },
                        error: function(xhr) {
                            var response = xhr.responseJSON;
                            Swal.fire({
                                title: "Error",
                                icon: "error",
                                text: response.message,
                                draggable: true
                            });
                        }
                    });
                }
            });
        })

        function refreshTable() {
            var table = $('#datatable').DataTable();
            table.ajax.reload(null, false);
        }
        // Variables con datos desde el backend
        var route = {
            start: {
                lat: {{ $route->latitude_start }},
                lng: {{ $route->longitude_start }}
            },
            end: {
                lat: {{ $route->latitude_end }},
                lng: {{ $route->longitude_end }}
            }
        };

        // Zona(s) con sus coordenadas (pasadas desde el backend)
        var zones = @json($perimeter);

        // Inicializar el mapa
        function initMap() {
            var mapOptions = {
                center: {
                    lat: route.start.lat,
                    lng: route.start.lng
                },
                zoom: 15
            };
            var map = new google.maps.Map(document.getElementById('map'), mapOptions);

            // Dibujar la ruta
            var routePath = new google.maps.Polyline({
                path: [route.start, route.end],
                geodesic: true,
                strokeColor: '#0000FF',
                strokeOpacity: 1.0,
                strokeWeight: 2,
                map: map
            });

            // Marcadores de inicio y fin de la ruta
            new google.maps.Marker({
                position: route.start,
                map: map,
                title: 'Inicio de la Ruta'
            });

            new google.maps.Marker({
                position: route.end,
                map: map,
                title: 'Final de la Ruta'
            });

            // Dibujar el perímetro de las zonas
            zones.forEach(function(zone) {
                var zonePolygon = new google.maps.Polygon({
                    paths: zone.coords, // Coordenadas de la zona
                    strokeColor: '#FF0000', // Color del perímetro
                    strokeOpacity: 0.8, // Opacidad del perímetro
                    strokeWeight: 2, // Grosor del perímetro
                    fillColor: '#FF0000', // Color de relleno
                    fillOpacity: 0.35, // Opacidad de relleno
                    map: map
                });

                // Opcional: agregar un marcador en el centro de la zona
                var center = getPolygonCenter(zone.coords);
                new google.maps.Marker({
                    position: center,
                    map: map,
                    title: zone.name
                });
            });
        }

        // Función para calcular el centro del polígono (opcional)
        function getPolygonCenter(coords) {
            var latSum = 0;
            var lngSum = 0;

            coords.forEach(function(coord) {
                latSum += coord.lat;
                lngSum += coord.lng;
            });

            var latCenter = latSum / coords.length;
            var lngCenter = lngSum / coords.length;

            return {
                lat: latCenter,
                lng: lngCenter
            };
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async
        defer></script>
@endsection
