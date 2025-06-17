<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('employee_search', 'Buscar empleado') !!}
        <div class="input-group">
            {!! Form::text('employee_search', old('employee_search', isset($employee) ? $employee->dni : null), [
                'class' => 'form-control',
                'id' => 'employee_search',
                'placeholder' => 'DNI del empleado',
                'maxlength' => 8,
                'inputmode' => 'numeric',
                'autocomplete' => 'off',
                isset($editMode) && $editMode ? 'readonly' : '',
            ]) !!}

            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="btnBuscarEmpleado">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <small id="employee_search_feedback" class="form-text text-danger"></small>
    </div>
    <div class="form-group col-6">
        {!! Form::label('names', 'Empleado') !!}
        {!! Form::text('names', old('names', isset($employee) ? $employee->names . ' ' . $employee->lastnames : null), [
            'class' => 'form-control',
            'id' => 'employee_name',
            'placeholder' => 'Nombre del empleado',
            'readonly',
        ]) !!}
        {!! Form::hidden('employee_id', old('employee_id', isset($employee) ? $employee->id : null), [
            'id' => 'employee_id',
        ]) !!}

    </div>
</div>
<div class="form-row">
    <div class="form-group col-2">
        {!! Form::label('year', 'Año') !!}
        {!! Form::selectRange('year', 2025, date('Y') + 1, old('year', isset($vacation) ? $vacation->year : date('Y')), [
            'class' => 'form-control',
            'id' => 'vacation_year',
            'required',
        ]) !!}
    </div>

    <div class="form-group col-5" id="group_start_date">
        {!! Form::label('start_date', 'Fecha de inicio') !!}
        {!! Form::date('start_date', null, [
            'class' => 'form-control',
            'id' => 'start_date',
            'required',
        ]) !!}
    </div>
    <div class="form-group col-5" id="group_end_date">
        {!! Form::label('end_date', 'Fecha de fin') !!}
        {!! Form::date('end_date', null, [
            'class' => 'form-control',
            'id' => 'end_date',
        ]) !!}
    </div>
</div>
<div class="form-row">
    <div class="col-4">
        <div class="form-group">
            {!! Form::label('days', 'Días programados') !!}
            {!! Form::number('days', null, [
                'class' => 'form-control',
                'id' => 'days',
                'min' => 1,
                'max' => 30,
                'readonly' => true,
            ]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('pending_days', 'Días pendientes') !!}
            {!! Form::number('pending_days', null, [
                'class' => 'form-control',
                'id' => 'pending_days',
                'readonly' => true,
            ]) !!}
        </div>
    </div>
    <div class="col-8">
        <div class="form-group">
            {!! Form::label('description', 'Descripción') !!}
            {!! Form::textarea('description', null, [
                'class' => 'form-control',
                'placeholder' => 'Agregue una descripción',
                'rows' => 5,
            ]) !!}
        </div>
    </div>
</div>

<script>
    $('#start_date, #end_date').on('change', function() {
        const start = new Date($('#start_date').val());
        const end = new Date($('#end_date').val());
        if (start && end && end >= start) {
            const days = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1; // ambos inclusive
            $('#days').val(days);
            $('#pending_days').val(30 - days);
        } else {
            $('#days').val('');
            $('#pending_days').val('');
        }
    });

    $(document).ready(function() {
        function buscarEmpleadoYValidar() {
            let dni = $('#employee_search').val();
            let year = $('#vacation_year').val();
            $('#employee_search_feedback').text('');
            $('#employee_id').val('');
            $('#employee_name').val('');

            if (dni.length === 8 && year) {
                // PRIMERO verifica el tipo de contrato
                $.ajax({
                    url: '/admin/employees/search-vacation',
                    type: 'GET',
                    data: {
                        dni
                    },
                    success: function(res) {
                        if (!res.success) {
                            $('#employee_search_feedback').text(res.message);
                            $('#employee_id').val('');
                            $('#employee_name').val('');
                            $('#days').val('');
                            $('#pending_days').val('');
                            $('#start_date, #end_date, #days, #description').prop('disabled', true);
                            $('#RegistrarBtn').prop('disabled', true);
                            return;
                        }

                        // SI ES EMPLEADO VÁLIDO, obtiene nombre y luego valida vacaciones
                        $('#employee_id').val(res.employee.id);
                        $('#employee_name').val(res.employee.full_name);
                        $('#employee_search_feedback').text('');

                        // Ahora consulta días de vacaciones:
                        $.ajax({
                            url: '/admin/vacations/check',
                            type: 'GET',
                            data: {
                                dni,
                                year
                            },
                            success: function(response) {
                                if (!response.success) {
                                    $('#employee_search_feedback').text(response
                                        .message);
                                    $('#employee_id').val('');
                                    $('#employee_name').val('');
                                    $('#days').val('');
                                    $('#pending_days').val('');
                                    $('#start_date, #end_date, #days, #description')
                                        .prop('disabled', true);
                                    $('#RegistrarBtn').prop('disabled', true);
                                } else {
                                    $('#pending_days').val(response.dias_pendientes);
                                    $('#start_date, #end_date, #days, #description')
                                        .prop('disabled', false);
                                    $('#RegistrarBtn').prop('disabled', false);
                                    if (response.dias_registrados > 0) {
                                        $('#employee_search_feedback').text(
                                            `Este empleado ya tiene ${response.dias_registrados} día(s) programados y le quedan ${response.dias_pendientes} disponible(s) en ${year}.`
                                        );
                                    }
                                    $('#days').attr('max', response.dias_pendientes);
                                }
                            }
                        });
                    }
                });
            } else {
                $('#employee_id').val('');
                $('#employee_name').val('');
                $('#employee_search_feedback').text('');
                $('#start_date, #end_date, #days, #description').prop('disabled', true);
                $('#RegistrarBtn').prop('disabled', true);
            }
        }

        // Ejecuta al cambiar DNI o año
        $('#employee_search').on('input', function() {
            if ($(this).val().length === 8) buscarEmpleadoYValidar();
        });
        $('#vacation_year').on('change', buscarEmpleadoYValidar);

        // Permitir solo números y máximo 8 caracteres en el input DNI
        $('#employee_search').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value.length > 8) value = value.slice(0, 8);
            $(this).val(value);
        });

        // Habilitar la búsqueda con Enter
        $('#employee_search').on('keypress', function(e) {
            if (e.which === 13) {
                buscarEmpleadoYValidar();
                e.preventDefault();
            }
        });
    });
</script>
