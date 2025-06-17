@extends('adminlte::page')

@section('title', 'Asistencias')

<!--@section('content_header')
@stop-->

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <h3>Asistencias</h3>
        </div>
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="filtro_dni" class="form-control" placeholder="DNI o nombre del empleado">
                </div>
                <div class="col-md-3">
                    <input type="date" id="filtro_fecha_inicio" class="form-control" placeholder="Fecha inicio">
                </div>
                <div class="col-md-3">
                    <input type="date" id="filtro_fecha_fin" class="form-control" placeholder="Fecha fin">
                </div>
                <div class="col-md-2">
                    <button id="btnFiltrar" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Filtrar</button>
                </div>
            </div>
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
