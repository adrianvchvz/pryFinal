<div class="form-row">
    <div class="form-group col-4">
        {!! Form::label('contract_type_id', 'Tipo de contrato') !!}
        {!! Form::select('contract_type_id', $typec, null, ['class' => 'form-control', 'required']) !!}
    </div>
    <div class="form-group col-8">
        {!! Form::label('employee_search', 'Buscar empleado') !!}
        <div class="input-group">
            {!! Form::text('employee_search', null, [
                'class' => 'form-control',
                'id' => 'employee_search',
                'placeholder' => 'Escriba el DNI del empleado',
                'maxlength' => 8,
                'inputmode' => 'numeric',
                'autocomplete' => 'off',
            ]) !!}
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" id="btnBuscarEmpleado">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <small id="employee_search_feedback" class="form-text text-danger"></small>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-6">
        {!! Form::label('names', 'Empleado') !!}
        {!! Form::text('names', null, [
            'class' => 'form-control',
            'id' => 'employee_name',
            'placeholder' => 'Nombre del empleado',
            'readonly',
        ]) !!}
        {!! Form::hidden('employee_id', null, ['id' => 'employee_id']) !!}
    </div>
    <div class="form-group col-3" id="group_start_date">
        {!! Form::label('start_date', 'Fecha de inicio') !!}
        {!! Form::date('start_date', null, [
            'class' => 'form-control',
            'id' => 'start_date',
            'required',
        ]) !!}
    </div>
    <div class="form-group col-3" id="group_end_date" style="display: none;">
        {!! Form::label('end_date', 'Fecha de fin') !!}
        {!! Form::date('end_date', null, [
            'class' => 'form-control',
            'id' => 'end_date',
        ]) !!}
    </div>

</div>
<div class="form-group">
    {!! Form::label('description', 'Descripción') !!}
    {!! Form::textarea('description', null, [
        'class' => 'form-control',
        'placeholder' => 'Agregue una descripción',
        'rows' => 5,
    ]) !!}
</div>
<div class="form-check">
    {!! Form::checkbox('status', 1, true, ['class' => 'form-check-input']) !!}
    {!! Form::label('status', 'Estado') !!}
</div>
<script>
    $(document).ready(function() {
        toggleDates();
        $('#contract_type_id').change(toggleDates);
    });

    function toggleDates() {
        var tipo = $('#contract_type_id option:selected').text().toUpperCase();
        if (tipo.includes('EVENTUAL')) {
            $('#group_end_date').show();
            $('#end_date').prop('required', true);
        } else {
            $('#group_end_date').hide();
            $('#end_date').prop('required', false);
            $('#end_date').val('');
        }
    }

    $(document).ready(function() {
        toggleDates();
        $('#contract_type_id').change(function() {
            toggleDates();
            // Limpiar cuando cambie el tipo de contrato
            $('#employee_name').val('');
            $('#employee_id').val('');
            $('#employee_search').val('');
            $('#employee_search_feedback').text('');
        });

        // Permitir solo números y máximo 8 caracteres
        $('#employee_search').on('input', function() {
            let value = $(this).val().replace(/\D/g, '');
            if (value.length > 8) value = value.slice(0, 8);
            $(this).val(value);

            // Si el input no es de longitud 8, limpia los datos
            if (value.length !== 8) {
                $('#employee_id').val('');
                $('#employee_name').val('');
                $('#employee_search_feedback').text('');
            }
        });

        // Buscar empleado por DNI + tipo de contrato
        $('#btnBuscarEmpleado').on('click', function() {
            let dni = $('#employee_search').val().trim();
            let contract_type_id = $('#contract_type_id').val(); // <-- importante
            $('#employee_search_feedback').text('');
            $('#employee_name').val('');
            $('#employee_id').val('');

            if (dni.length !== 8) {
                $('#employee_search_feedback').text('El DNI debe tener 8 números.');
                return;
            }

            $.ajax({
                url: '/admin/employees/search',
                type: 'GET',
                data: {
                    dni: dni,
                    contract_type_id: contract_type_id // <-- se manda al backend
                },
                success: function(response) {
                    if (response.success && response.employee) {
                        $('#employee_name').val(response.employee.full_name);
                        $('#employee_id').val(response.employee.id);
                    } else {
                        $('#employee_search_feedback').text(response.message ||
                            'Empleado no encontrado.');
                        $('#employee_id').val('');
                    }
                },
                error: function(xhr) {
                    $('#employee_search_feedback').text('Error en la búsqueda.');
                    $('#employee_id').val('');
                }
            });
        });

        $('#employee_search').on('keypress', function(e) {
            if (e.which === 13) {
                $('#btnBuscarEmpleado').click();
                e.preventDefault();
            }
        });
    });
</script>
