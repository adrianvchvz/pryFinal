@extends('adminlte::page')

@section('title', 'Marcas')

<!--@section('content_header')
@stop-->

@section('content')
    <style>
        .page-container {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding: 20px 0;
        }

        .brands-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 0 10px;
        }

        .brands-title {
            display: flex;
            align-items: center;
            color: #6c757d;
            font-size: 1.5rem;
            font-weight: 500;
            margin: 0;
        }

        .brands-title::before {
            margin-right: 12px;
            font-size: 1.3rem;
        }

        .btn-new-brand {
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
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .btn-new-brand:hover {
            background-color: #a7cd6a;
            color: white;
            border-color: #a7cd6a;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(167, 205, 106, 0.3);
        }

        .btn-new-brand::before {
            content: "+";
            font-size: 1.2rem;
            font-weight: bold;
        }

       
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .dataTables_wrapper {
            padding: 0;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            padding: 20px 25px 10px;
        }

        .dataTables_wrapper .dataTables_filter {
            text-align: right;
        }

        .dataTables_wrapper .dataTables_filter label {
            font-weight: 500;
            color: #6c757d;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 8px 12px;
            margin-left: 10px;
            font-size: 14px;
        }

        .dataTables_wrapper .dataTables_length label {
            font-weight: 500;
            color: #6c757d;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 6px 20px;
            margin: 0 8px;
        }

        #datatable {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0;
        }

        #datatable thead th {
            background-color: #f1f8ec;
            color: #397044;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 18px 15px;
            border: none;
            border-bottom: 1px solid #e8f5e1;
            text-align: left;
        }

        #datatable tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: background-color 0.2s ease;
        }

        #datatable tbody tr:hover {
            background-color: #fafcf8;
        }

        #datatable tbody td {
            padding: 15px;
            vertical-align: middle;
            border: none;
            font-size: 14px;
            color: #495057;
        }

        
        .brand-logo {
            width: 40px;
            height: 40px;
            border-radius: 6px;
            object-fit: cover;
            border: 1px solid #e0e0e0;
        }

        .brand-name {
            font-weight: 500;
            color: #2d3436;
        }

        .brand-description {
            color: #6c757d;
            font-size: 13px;
        }

        .date-text {
            color: #6c757d;
            font-size: 13px;
        }

        
        .action-btn {
            padding: 6px 8px;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin: 0 2px;
        }

        .btn-edit {
            background-color: transparent;
            color: #a7cd6a;
        }

        .btn-edit:hover {
            background-color: #a7cd6a;
            color: white;
        }

        .btn-delete {
            background-color: transparent;
            color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #dc3545;
            color: white;
        }

        
        .dataTables_wrapper .dataTables_paginate {
            padding: 20px 25px;
            text-align: center;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 8px 12px;
            margin: 0 2px;
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            color: #6c757d !important;
            background: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #f8f9fa !important;
            border-color: #a7cd6a !important;
            color: #a7cd6a !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #397044 !important;
            border-color: #397044 !important;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_info {
            padding: 20px 25px;
            color: #6c757d;
            font-size: 13px;
        }

        
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }

        .modal-header {
            background-color: #f1f8ec;
            border: none;
            border-radius: 12px 12px 0 0;
            padding: 20px 25px;
            border-color: #397044
        }

        .modal-title {
            color: #397044;
            font-weight: 500;
            font-size: 1.3rem;
        }

        .modal-header .close {
            color: 397044;
            opacity: 0.9;
            font-size: 1.5rem;
            text-shadow: none;
        }

        .modal-header .close:hover {
            opacity: 1;
            color: white;
        }

        .modal-body {
            padding: 30px;
        }

        
        @media (max-width: 768px) {
            .brands-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .btn-new-brand {
                align-self: flex-end;
            }
        }
    </style>

    <div class="page-container">
        <div class="container-fluid">
           
            <div class="brands-header">
                <h1 class="brands-title">Marcas</h1>
                <button type="button" class="btn btn-new-brand" id="btnNuevo">
                    Nueva
                </button>
            </div>

            
            <div class="table-container">
                <table class="display" id="datatable">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Creado</th>
                            <th>Actualizado</th>
                            <th>Acciones</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

  
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
                "ajax": "{{ route('admin.brands.index') }}",
                "columns": [{
                        "data": "logo",
                        "width": "4%",
                        "orderable": false,
                        "searchable": false
                    },
                    {
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
                        "searchable": false,
                        "width": "4%",

                    },
                    {
                        "data": "delete",
                        "orderable": false,
                        "searchable": false,
                        "width": "4%",

                    },
                ]
            });
        })

        $('#btnNuevo').click(function() {
            $.ajax({
                url: "{{ route('admin.brands.create') }}",
                type: "GET",
                success: function(response) {
                    $('.modal-title').html("Nueva marca");
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
                url: "{{ route('admin.brands.edit', 'id') }}".replace('id', id),
                type: "GET",
                success: function(response) {
                    $('.modal-title').html("Editar marca");
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