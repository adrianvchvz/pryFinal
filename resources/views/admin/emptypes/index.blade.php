@extends('adminlte::page')

@section('title', 'Tipos de empleados')

<!--@section('content_header')
@stop-->

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary float-right" id="btnNuevo"><i class="fas fa-folder-plus"></i>
                Nuevo</button>
            <h3>Tipos de empleados</h3>
        </div>
        <div class="card-body">
            <table class="display" id="datatable">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Creación</th>
                        <th>Actualización</th>
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


@section('js')

    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                },
                "ajax": "{{ route('admin.emptypes.index') }}",
                "columns": [{
                        "data": "name"
                    },
                    {
                        "data": "description"
                    },
                    {
                        "data": "created_at"
                    },
                    {
                        "data": "updated_at"
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
                ]
            });
        })

        $('#btnNuevo').click(function() {
            $.ajax({
                url: "{{ route('admin.emptypes.create') }}",
                type: "GET",
                success: function(response) {
                    $('.modal-title').html("Nuevo tipo de empleado");
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

        $(document).on('click', '.btnEditar', function() {
            var id = $(this).attr("id");
            $.ajax({
                url: "{{ route('admin.emptypes.edit', 'id') }}".replace('id', id),
                type: "GET",
                success: function(response) {
                    $('.modal-title').html("Editar tipo de empleado");
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
    </script>
@endsection
