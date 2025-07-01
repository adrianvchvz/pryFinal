@extends('adminlte::page')

@section('title', 'Programación')

<!--@section('content_header')
@stop-->

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-success float-right ml-2" id="btnEditar" data-id="{{ $schedule->id }}">
                <i class="fas fa-folder-plus"></i> Editar
            </button>
            <h3>Días programados</h3>
        </div>
        <div class="card-body">
            <table class="display" id="datatable">
                <thead>
                    <tr>
                        <th>Programación</th>
                        <th>Día</th>
                        <th>Estado</th>
                        <th>Recorrido</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.schedules.index') }}" class="btn btn-danger float-right">
                <i></i>Retornar</a>
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
                <div class="modal-body">...</div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .status-completo {
            background-color: #d4edda !important;
        }

        .status-incompleto {
            background-color: #f8d7da !important;
        }
    </style>
@endsection

@section('js')

    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                },
                "ajax": "{{ route('admin.schedules.show', $schedule->id) }}",
                "columns": [{
                        "data": "name"
                    },
                    {
                        "data": "date"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "trip_status"
                    },
                    {
                        "data": "start_trip",
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        "data": "show",
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        "data": "delete",
                        "orderable": false,
                        "searchable": false
                    },
                ],
                "rowCallback": function(row, data) {
                    if (data.status === 'COMPLETO') {
                        $(row).addClass('status-completo');
                    } else if (data.status === 'INCOMPLETO') {
                        $(row).addClass('status-incompleto');
                    }

                    if (data.trip_status === 'INICIADO') {
                        $(row).addClass('table-warning');
                    } else if (data.trip_status === 'FINALIZADO') {
                        $(row).addClass('table-success');
                    }
                }

            });
        })

        $('#btnEditar').click(function() {
            let scheduleId = $(this).data('id');

            $.ajax({
                url: '/admin/schedules/' + scheduleId + '/edit-days',
                type: 'GET',
                success: function(response) {
                    $('.modal-title').html("Asignar reemplazos");
                    $('#ModalCenter .modal-body').html(response);
                    $('#ModalCenter').modal('show');

                    $('#ModalCenter form').on('submit', function(e) {
                        e.preventDefault();
                        let form = $(this);
                        let formData = new FormData(this);
                        $.ajax({
                            url: form.attr('action'),
                            type: form.attr('method'),
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                $('#ModalCenter').modal('hide');
                                refreshTable();
                                Swal.fire("Éxito", response.message, "success");
                            },
                            error: function(xhr) {
                                Swal.fire("Error", xhr.responseJSON.message,
                                    "error");
                            }
                        });
                    });
                },
                error: function(xhr) {
                    Swal.fire("Error", "No se pudo cargar el formulario", "error");
                }
            });
        });


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

        $(document).on('submit', '.frmStartTrip', function(e) {
            e.preventDefault();
            let form = $(this);

            Swal.fire({
                title: "¿Iniciar recorrido?",
                text: "Esto marcará como iniciado todas las zonas del día.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, iniciar"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            refreshTable();
                            Swal.fire("Éxito", response.message, "success");
                        },
                        error: function(xhr) {
                            Swal.fire("Error", xhr.responseJSON?.message ||
                                "No se pudo iniciar", "error");
                        }
                    });
                }
            });
        });


        function refreshTable() {
            var table = $('#datatable').DataTable();
            table.ajax.reload(null, false);
        }
    </script>
@endsection
