{!! Form::model($vacation, ['route' => ['admin.vacations.update', $vacation], 'method' => 'PUT']) !!}
    @include('admin.vacations.template.form', ['employee' => $employee, 'vacation' => $vacation, 'editMode' => true])
    <button type='submit' class="btn btn-success" id="RegistrarBtn">
        <i class="fas fa-cloud-upload-alt"></i>Actualizar
    </button>
{!! Form::close() !!}
