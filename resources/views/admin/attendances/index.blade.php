@extends('adminlte::page')

@section('title', 'Asistencias')

<!--@section('content_header')
@stop-->

@section('content')
    <style>
        
        .page-container {
            background-color: #f8f9fa;
            min-height: 100vh;
            padding: 20px 0;
        }

        .attendance-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 0 10px;
        }

        .attendance-title {
            display: flex;
            align-items: center;
            color: #6c757d;
            font-size: 1.5rem;
            font-weight: 500;
            margin: 0;
        }

        .attendance-title::before {
           
            margin-right: 12px;
            font-size: 1.3rem;
        }

        
        .filters-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            padding: 25px;
            margin-bottom: 20px;
        }

        .filters-title {
            color: #397044;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .filters-title::before {
            content: "🔍";
            margin-right: 8px;
        }

        .filter-input {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.2s ease;
            background-color: #fafafa;
        }

        .filter-input:focus {
            border-color: #a7cd6a;
            box-shadow: 0 0 0 0.2rem rgba(167, 205, 106, 0.25);
            background-color: white;
            outline: none;
        }

        .filter-input::placeholder {
            color: #9ca3af;
            font-size: 13px;
        }

        .btn-filter {
            background: #a7cd6a;
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(167, 205, 106, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-filter:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(167, 205, 106, 0.4);
            color: white;
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
            padding: 6px 10px;
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

        
        .employee-name {
            font-weight: 500;
            color: #2d3436;
        }

        .date-text {
            color: #6c757d;
            font-size: 13px;
        }

        .time-text {
            font-weight: 500;
            color: #397044;
            font-family: 'Courier New', monospace;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-presente {
            background-color: #d4edda;
            color: #155724;
        }

        .status-ausente {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-tardanza {
            background-color: #fff3cd;
            color: #856404;
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

        .dataTables_wrapper .dataTables_processing {
            background: white;
            color: rgba(167, 205, 106, 0.9);
            border-radius: 8px;
            border: none;
            font-weight: 500;
        }

        
        @media (max-width: 768px) {
            .attendance-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
            
            .filters-container {
                padding: 20px 15px;
            }
            
            .filter-input {
                margin-bottom: 10px;
            }
        }
    </style>

    <div class="page-container">
        <div class="container-fluid">
           
            <div class="attendance-header">
                <h1 class="attendance-title">Asistencias</h1>
            </div>

            
            <div class="filters-container">
                <div class="filters-title">Filtros de búsqueda</div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <input type="text" id="filtro_dni" class="form-control filter-input" 
                               placeholder="DNI o nombre del empleado">
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="date" id="filtro_fecha_inicio" class="form-control filter-input" 
                               placeholder="Fecha inicio">
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="date" id="filtro_fecha_fin" class="form-control filter-input" 
                               placeholder="Fecha fin">
                    </div>
                    <div class="col-md-2 mb-3">
                        <button id="btnFiltrar" class="btn btn-filter w-100">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                    </div>
                </div>
            </div>

            
            <div class="table-container">
                <table class="display" id="datatable">
                    <thead>
                        <tr>
                            <th>Empleado</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
    <script>
        var table;
        $(document).ready(function() {
            table = $('#datatable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                },
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('admin.attendances.filter') }}",
                    "data": function(d) {
                        d.dni = $('#filtro_dni').val();
                        d.fecha_inicio = $('#filtro_fecha_inicio').val();
                        d.fecha_fin = $('#filtro_fecha_fin').val();
                    }
                },
                "columns": [{
                        "data": "employee_fullname"
                    },
                    {
                        "data": "date"
                    },
                    {
                        "data": "time_in"
                    },
                    {
                        "data": "status"
                    }
                ],
                "searching": false,
                "lengthChange": false,
            });

            $('#btnFiltrar').click(function() {
                table.ajax.reload();
            });

            $('#filtro_dni, #filtro_fecha_inicio, #filtro_fecha_fin').on('keypress', function(e) {
                if (e.which == 13) {
                    table.ajax.reload();
                }
            });
        });
    </script>
@endsection