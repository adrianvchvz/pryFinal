@extends('adminlte::page')

@section('title', 'Zonas')

@section('content')
<style>
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
}

.content-wrapper {
    background-color: var(--bg-gray);
    min-height: 100vh;
    padding: 20px;
}

.card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    background: var(--white);
    margin-bottom: 20px;
}

.card-header {
    background: var(--white);
    border-bottom: 2px solid var(--border-color);
    border-radius: 12px 12px 0 0 !important;
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h3, .card-header h4 {
    margin: 0;
    color: #333;
    font-size: 24px;
    font-weight: 600;
}

.btn-primary {
    background: var(--primary-green);
    border: var(--primary-green);
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(139, 195, 74, 0.3);
}

.btn-primary:hover {
    background: var(--dark-green);
    border-color: var(--dark-green);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(139, 195, 74, 0.4);
}

.btn-primary i {
    margin-right: 8px;
}

.card-body {
    padding: 25px;
}

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
}

.dataTables_filter input {
    border: 2px solid var(--border-color);
    border-radius: 6px;
    padding: 8px 12px;
    margin-left: 10px;
    transition: border-color 0.3s ease;
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
}

#datatable thead th {
    background: var(--light-green);
    color: #397044;
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
}

#datatable tbody tr {
    transition: background-color 0.2s ease;
}

#datatable tbody tr:hover {
    background-color: #F8F9FA;
}

.info-label {
    font-weight: 600;
    color: #333;
    font-size: 15px;
}

.info-value {
    color: #666;
    font-size: 14px;
    font-weight: 500;
}

.date-info {
    font-family: 'Segoe UI';
    font-size: 12px;
    color: #000000;
    background: #F8F9FA;
    padding: 4px 8px;
    border-radius: 4px;
    display: inline-block;
}

.btn-danger {
    background: #F44336;
    border: none;
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    transition: all 0.3s ease;
    min-width: 35px;
}

.btn-danger:hover {
    background: #D32F2F;
    transform: translateY(-1px);
}

.modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    background: var(--light-green);
    color: #397044;
    border-radius: 12px 12px 0 0;
    padding: 20px 25px;
}

.modal-title {
    font-weight: 600;
    font-size: 18px;
}

.modal-header .close {
    color: #397044;
    opacity: 0.8;
    font-size: 24px;
}

.modal-header .close:hover {
    opacity: 1;
}

.modal-body {
    padding: 25px;
    max-height: 70vh;
    overflow-y: auto;
}

.card-footer {
    background: var(--white);
    border-top: 2px solid var(--border-color);
    border-radius: 0 0 12px 12px !important;
    padding: 15px 25px;
}

.btn-return {
    background: var(--danger-red);
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(244, 67, 54, 0.3);
}

.btn-return:hover {
    background: #D32F2F;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(211, 47, 47, 0.4);
}

.btn-return i {
    margin-right: 8px;
}

.description-text {
    color: #010101;
    font-size: 14px;
    word-break: break-word;
    margin-top: 5px;
    display: block;
}

@media (max-width: 768px) {
    .row > div {
        width: 100%;
    }
    
    .col-3, .col-9 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .card-body {
        padding: 15px;
    }
    
    #datatable {
        font-size: 12px;
    }
    
    #datatable thead th,
    #datatable tbody td {
        padding: 8px 6px;
    }
    
    .modal-xl .modal-dialog {
        max-width: 95%;
        margin: 10px;
    }
}
</style>

<div class="p-2"></div>
<div class="card">
    <div class="card-header">
        <h3>Perímetro de la zona</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 col-12">
                <div class="card">
                    <div class="card-body">
                        <label>
                            <span class="info-label">Zona:</span> 
                            <span class="info-value">{{ $zone->name }}</span>
                        </label>
                        <br>
                        <label>
                            <span class="info-label">Área:</span> 
                            <span class="info-value">{{ $zone->area }}</span>
                        </label>
                        <br>
                        <label>
                            <span class="info-label">Descripción:</span>
                            <span class="description-text">
                                {{ $zone->description }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-12">
                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-primary float-right" id="btnNuevo" data-id={{ $zone->id }}>
                            <i class="fas fa-plus"></i> Nueva Coordenada
                        </button>
                        <h4>Coordenadas</h4>
                    </div>
                    <div class="card-body">
                        <table class="display" id="datatable">
                            <thead>
                                <tr>
                                    <th>Latitud</th>
                                    <th>Longitud</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.zones.index') }}" class="btn btn-return float-right">
            <i class="fas fa-arrow-left"></i> Retornar
        </a>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
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
                "ajax": "{{ route('admin.zones.show', $zone->id) }}",
                "columns": [{
                        "data": "latitude",
                        "orderable": false,
                        "searchable": false,
                    },
                    {
                        "data": "longitude",
                        "orderable": false,
                        "searchable": false,
                    },
                    {
                        "data": "delete",
                        "orderable": false,
                        "searchable": false,
                    }
                ],
            });
        })

        $('#btnNuevo').click(function() {
            var id = $(this).attr('data-id');

            $.ajax({
                url: "{{ route('admin.zonecoords.edit', '_id') }}".replace('_id', id),
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
    </script>
@endsection