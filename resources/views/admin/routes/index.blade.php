@extends('adminlte::page')

@section('title', 'Rutas')

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

.card-header h3 {
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

.route-name {
    font-weight: 600;
    color: #333;
    font-size: 15px;
}

.route-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background: #E8F5E8;
    color: #2E7D32;
}

.status-inactive {
    background: #FFEBEE;
    color: #C62828;
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

.btnMap {
    background: #FF9800;
    border: none;
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    transition: all 0.3s ease;
    margin-right: 5px;
    min-width: 35px;
}

.btnMap:hover {
    background: #F57C00;
    transform: translateY(-1px);
}

.btnEditar {
    background: #2196F3;
    border: none;
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    transition: all 0.3s ease;
    margin-right: 5px;
    min-width: 35px;
}

.btnEditar:hover {
    background: #1976D2;
    transform: translateY(-1px);
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

#datatable th:nth-last-child(-n+3),
#datatable td:nth-last-child(-n+3) {
    width: 50px;
    text-align: center;
}

.dataTables_info {
    color: var(--text-gray);
    font-size: 14px;
    margin-top: 15px;
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
}

.dataTables_paginate .paginate_button:hover {
    background: var(--light-green) !important;
    border-color: var(--primary-green) !important;
    color: #2E7D32 !important;
}

.dataTables_paginate .paginate_button.current {
    background: var(--primary-green) !important;
    border-color: var(--primary-green) !important;
    color: white !important;
}

.routes-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 0 10px;
}

.routes-title {
    display: flex;
    align-items: center;
    color: #6c757d;
    font-size: 1.5rem;
    font-weight: 500;
    margin: 0;
}

.routes-title::before {
    margin-right: 12px;
    font-size: 1.3rem;
}

.btn-new-route {
    background-color: white;
    border: 1px solid #e0e0e0;
    color: #397044;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.btn-new-route:hover {
    background-color: #a7cd6a;
    color: white;
    border-color: #a7cd6a;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(167, 205, 106, 0.3);
}

.btn-new-route::before {
    content: "+";
    font-size: 1.2rem;
    font-weight: bold;
}

.dataTables_processing {
    background: rgba(255,255,255,0.9);
    color: var(--primary-green);
    font-weight: 600;
}

.swal2-popup {
    border-radius: 12px;
}

.swal2-confirm {
    background: var(--primary-green) !important;
    border-radius: 6px !important;
}

.swal2-cancel {
    border-radius: 6px !important;
}

@media (max-width: 768px) {
    .routes-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .routes-title {
        font-size: 1.2rem;
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
    
    .modal-lg .modal-dialog {
        max-width: 95%;
        margin: 10px;
    }
}

[data-toggle="tooltip"] {
    cursor: help;
}
</style>

<div class="p-2"></div>

<div class="routes-header">
    <h1 class="routes-title">Rutas</h1>
    <button type="button" class="btn btn-new-route" id="btnNuevo">
        <i class="fas fa-folder-plus"></i> Nuevo
    </button>
</div>

<div class="card">
    <div class="card-body">
        <table class="display" id="datatable">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Creación</th>
                    <th>Actualización</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            },
            "ajax": "{{ route('admin.routes.index') }}",
            "columns": [{
                    "data": "name",
                    "render": function(data, type, row) {
                        return '<span class="route-name">' + data + '</span>';
                    }
                },
                {
                    "data": "status",
                    "render": function(data, type, row) {
                        var statusClass = data == 'Activo' ? 'status-active' : 'status-inactive';
                        return '<span class="route-status ' + statusClass + '">' + data + '</span>';
                    }
                },
                {
                    "data": "created_at",
                    "render": function(data, type, row) {
                        return '<span class="date-info">' + data + '</span>';
                    }
                },
                {
                    "data": "updated_at",
                    "render": function(data, type, row) {
                        return '<span class="date-info">' + data + '</span>';
                    }
                },
                {
                    "data": "gps",
                    "orderable": false,
                    "searchable": false,
                    "width": "4%"
                },
                {
                    "data": "edit",
                    "orderable": false,
                    "searchable": false,
                    "width": "4%"
                },
                {
                    "data": "delete",
                    "orderable": false,
                    "searchable": false,
                    "width": "4%"
                }
            ]
        });
    })

    $('#btnNuevo').click(function() {
        $.ajax({
            url: "{{ route('admin.routes.create') }}",
            type: "GET",
            success: function(response) {
                $('.modal-title').html("Nueva ruta");
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

    $(document).on('click', '.btnMap', function() {
        var id = $(this).attr("id");

        $.ajax({
            url: "{{ route('admin.routes.show', 'id') }}".replace('id', id),
            type: "GET",
            success: function(response) {
                $('.modal-title').html("Asignar zona en ruta");
                $('#ModalCenter .modal-body').html(response);
                $('#ModalCenter').modal('show');
            }
        });
    });

    $(document).on('click', '.btnEditar', function() {
        var id = $(this).attr("id");
        $.ajax({
            url: "{{ route('admin.routes.edit', 'id') }}".replace('id', id),
            type: "GET",
            success: function(response) {
                $('.modal-title').html("Editar Ruta");
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