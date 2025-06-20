@extends('adminlte::page')

@section('title', 'Rutas')

@section('content')

<style>
/* Variables CSS */
:root {
    --primary-green: #8BC34A;
    --dark-green: #7CB342;
    --light-green: #f1f8ec;
    --bg-gray: #F5F5F5;
    --text-gray: #666;
    --border-color: #E0E0E0;
    --white: #FFFFFF;
    --success-green: #4CAF50;
    --warning-orange: #FF9800;
    --danger-red: #F44336;
    --info-blue: #2196F3;
    --shadow-light: 0 2px 10px rgba(0,0,0,0.1);
    --shadow-medium: 0 4px 15px rgba(0,0,0,0.15);
    --shadow-heavy: 0 10px 30px rgba(0,0,0,0.2);
}

/* Layout general */
.content-wrapper {
    background-color: var(--bg-gray);
    min-height: 100vh;
    padding: 20px;
}

/* Cards principales */
.card {
    border: none;
    border-radius: 12px;
    box-shadow: var(--shadow-light);
    background: var(--white);
    margin-bottom: 20px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.card:hover {
    box-shadow: var(--shadow-medium);
    transform: translateY(-2px);
}

/* Header de cards */
.card-header {
    background: linear-gradient(135deg, var(--light-green) 0%, #e8f5d8 100%);
    border-bottom: 2px solid var(--border-color);
    border-radius: 12px 12px 0 0 !important;
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3 {
    margin: 0;
    color: #2E7D32;
    font-size: 24px;
    font-weight: 600;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.card-header h4 {
    margin: 0;
    color: #2E7D32;
    font-size: 18px;
    font-weight: 600;
}

/* Body de cards */
.card-body {
    padding: 25px;
    background: var(--white);
}

/* Card de información de ruta */
.route-info-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-left: 4px solid var(--primary-green);
    margin-bottom: 20px;
}

.route-info-card .card-body {
    padding: 20px;
}

/* Labels de información */
.info-label {
    display: block;
    margin-bottom: 15px;
    padding: 12px;
    background: var(--white);
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border-left: 3px solid var(--primary-green);
}

.info-label .font-weight-bold {
    color: #2E7D32;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-label .font-weight-normal {
    color: #333;
    font-size: 15px;
    margin-left: 10px;
}

/* Coordenadas destacadas */
.coordinates {
    background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
    color: #1565C0;
    padding: 4px 8px;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-weight: 600;
    font-size: 13px;
    display: inline-block;
    margin-left: 8px;
    box-shadow: 0 1px 3px rgba(21, 101, 192, 0.2);
}

/* Botones */
.btn {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn i {
    font-size: 14px;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
    color: white;
    padding: 10px 18px;
    box-shadow: 0 2px 8px rgba(139, 195, 74, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, var(--dark-green) 0%, #689F38 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(139, 195, 74, 0.4);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, var(--danger-red) 0%, #D32F2F 100%);
    color: white;
    padding: 10px 18px;
    box-shadow: 0 2px 8px rgba(244, 67, 54, 0.3);
}

.btn-danger:hover {
    background: linear-gradient(135deg, #D32F2F 0%, #C62828 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
    color: white;
}

/* Card de mapa */
.map-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-left: 4px solid var(--info-blue);
}

.map-card .card-header {
    background: linear-gradient(135deg, #E3F2FD 0%, #BBDEFB 100%);
    color: #1565C0;
    font-weight: 600;
}

#map {
    border-radius: 8px;
    box-shadow: var(--shadow-medium);
    overflow: hidden;
    transition: all 0.3s ease;
}

#map:hover {
    box-shadow: var(--shadow-heavy);
    transform: scale(1.02);
}

/* DataTable */
.dataTables_wrapper {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.dataTables_length,
.dataTables_filter {
    margin-bottom: 20px;
}

.dataTables_length label,
.dataTables_filter label {
    color: var(--text-gray);
    font-weight: 500;
    font-size: 14px;
}

.dataTables_filter input {
    border: 2px solid var(--border-color);
    border-radius: 6px;
    padding: 8px 12px;
    margin-left: 10px;
    transition: all 0.3s ease;
    font-size: 14px;
}

.dataTables_filter input:focus {
    border-color: var(--primary-green);
    outline: none;
    box-shadow: 0 0 0 3px rgba(139, 195, 74, 0.1);
}

#datatable {
    width: 100% !important;
    border-collapse: separate;
    border-spacing: 0;
    background: var(--white);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: var(--shadow-light);
}

#datatable thead th {
    background: linear-gradient(135deg, var(--light-green) 0%, #e8f5d8 100%);
    color: #2E7D32;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    padding: 15px 12px;
    border: none;
    position: relative;
}

#datatable thead th:first-child {
    border-radius: 8px 0 0 0;
}

#datatable thead th:last-child {
    border-radius: 0 8px 0 0;
}

#datatable tbody td {
    padding: 15px 12px;
    border-bottom: 1px solid #F0F0F0;
    vertical-align: middle;
    font-size: 14px;
    color: #333;
}

#datatable tbody tr {
    transition: all 0.2s ease;
}

#datatable tbody tr:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transform: scale(1.01);
}

/* Nombre de zona destacado */
.zone-name {
    font-weight: 600;
    color: #2E7D32;
    font-size: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.zone-name::before {
    content: "📍";
    font-size: 16px;
}

/* Botón de eliminar en tabla */
.delete-btn {
    background: linear-gradient(135deg, var(--danger-red) 0%, #D32F2F 100%);
    border: none;
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 12px;
    transition: all 0.3s ease;
    min-width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.delete-btn:hover {
    background: linear-gradient(135deg, #D32F2F 0%, #C62828 100%);
    transform: translateY(-1px) scale(1.05);
    box-shadow: 0 3px 8px rgba(244, 67, 54, 0.4);
}

/* Modal */
.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: var(--shadow-heavy);
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, var(--light-green) 0%, #e8f5d8 100%);
    color: #2E7D32;
    border-radius: 12px 12px 0 0;
    padding: 20px 25px;
    border-bottom: 2px solid var(--border-color);
}

.modal-title {
    font-weight: 600;
    font-size: 18px;
    margin: 0;
}

.modal-header .close {
    color: #2E7D32;
    opacity: 0.8;
    font-size: 24px;
    font-weight: 300;
    transition: all 0.2s ease;
}

.modal-header .close:hover {
    opacity: 1;
    transform: scale(1.1);
}

.modal-body {
    padding: 25px;
    max-height: 70vh;
    overflow-y: auto;
    background: var(--white);
}

/* Footer */
.card-footer {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-top: 2px solid var(--border-color);
    padding: 20px 25px;
    border-radius: 0 0 12px 12px;
}

/* Paginación */
.dataTables_info {
    color: var(--text-gray);
    font-size: 14px;
    margin-top: 15px;
    font-weight: 500;
}

.dataTables_paginate {
    margin-top: 15px;
}

.dataTables_paginate .paginate_button {
    padding: 8px 12px !important;
    margin: 0 2px;
    border-radius: 6px !important;
    border: 1px solid var(--border-color) !important;
    background: white !important;
    color: var(--text-gray) !important;
    transition: all 0.2s ease !important;
}

.dataTables_paginate .paginate_button:hover {
    background: var(--light-green) !important;
    border-color: var(--primary-green) !important;
    color: #2E7D32 !important;
    transform: translateY(-1px);
}

.dataTables_paginate .paginate_button.current {
    background: var(--primary-green) !important;
    border-color: var(--primary-green) !important;
    color: white !important;
}

/* SweetAlert personalizado */
.swal2-popup {
    border-radius: 12px;
    box-shadow: var(--shadow-heavy);
}

.swal2-confirm {
    background: var(--primary-green) !important;
    border-radius: 6px !important;
    font-weight: 500 !important;
}

.swal2-cancel {
    border-radius: 6px !important;
    font-weight: 500 !important;
}

/* Efectos de carga */
.dataTables_processing {
    background: rgba(255,255,255,0.95) !important;
    color: var(--primary-green) !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
    box-shadow: var(--shadow-medium) !important;
}

/* Responsive */
@media (max-width: 768px) {
    .content-wrapper {
        padding: 10px;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .card-header {
        padding: 15px;
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .card-header h3 {
        font-size: 20px;
    }
    
    .info-label {
        padding: 10px;
        margin-bottom: 10px;
    }
    
    .info-label .font-weight-bold {
        display: block;
        margin-bottom: 5px;
    }
    
    .info-label .font-weight-normal {
        margin-left: 0;
    }
    
    .coordinates {
        display: block;
        margin-left: 0;
        margin-top: 5px;
        text-align: center;
    }
    
    #map {
        height: 300px !important;
    }
    
    #datatable {
        font-size: 12px;
    }
    
    #datatable thead th,
    #datatable tbody td {
        padding: 8px 6px;
    }
    
    .modal-lg .modal-dialog {
        max-width: 95%;
        margin: 10px;
    }
    
    .btn {
        padding: 8px 12px;
        font-size: 12px;
    }
}

/* Animaciones adicionales */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.5s ease-out;
}

.card:nth-child(2) {
    animation-delay: 0.1s;
}

.card:nth-child(3) {
    animation-delay: 0.2s;
}

/* Tooltips */
[data-toggle="tooltip"] {
    cursor: help;
}

/* Estados de botones */
.btn:focus {
    box-shadow: 0 0 0 3px rgba(139, 195, 74, 0.25);
    outline: none;
}

.btn:active {
    transform: translateY(1px);
}

/* Mejoras visuales para el mapa */
.map-container {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
}

.map-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 0%, rgba(139, 195, 74, 0.05) 50%, transparent 100%);
    pointer-events: none;
    z-index: 1;
}

    </style>


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
