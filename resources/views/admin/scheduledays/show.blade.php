@extends('adminlte::page')

@section('title', 'Programación')

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <h3>Asignaciones del día {{ $scheduleday->date }}</h3>
        </div>
        <div class="card-body">
            <table class="display" id="datatable">
                <thead>
                    <tr>
                        <th>Zona</th>
                        <th>Vehículo</th>
                        <th>Turno</th>
                        <th>Conductor</th>
                        <th>Ayudantes</th>
                        <th>Estado</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.schedules.show', $scheduleday->schedule_id) }}" class="btn btn-danger float-right">
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
                "ajax": "{{ route('admin.scheduledays.show', $scheduleday->id) }}",
                "columns": [{
                        "data": "zone_name"
                    },
                    {
                        "data": "vehicle_name"
                    },
                    {
                        "data": "shift_name"
                    },
                    {
                        "data": "conductor_fullname"
                    },
                    {
                        "data": "ayudantes"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": "edit",
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        "data": "delete",
                        "orderable": false,
                        "searchable": false
                    }
                ],
                "rowCallback": function(row, data) {
                    if (data.status === 'COMPLETO') {
                        $(row).addClass('status-completo');
                    } else if (data.status === 'INCOMPLETO') {
                        $(row).addClass('status-incompleto');
                    }
                }
            });
        });

        $('#btnNuevo').click(function() {
            $.ajax({
                url: "{{ route('admin.scheduledetails.create', ['scheduleday_id' => $scheduleday->id]) }}",
                type: "GET",
                success: function(response) {
                    $('.modal-title').html("Nueva asignación del día");
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
        });

        $(document).on('click', '.btnEditar', function() {
            var id = $(this).attr("id");
            $.ajax({
                url: "{{ route('admin.scheduledetails.edit', 'id') }}".replace('id', id),
                type: "GET",
                success: function(response) {
                    $('.modal-title').html("Editar asignación del día");
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
                confirmButtonText: "Sí, eliminar!"
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
        });

        function refreshTable() {
            var table = $('#datatable').DataTable();
            table.ajax.reload(null, false);
        }
    </script>
@endsection
