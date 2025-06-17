{!! Form::model($employee, ['route' => ['admin.employees.update', $employee], 'method' => 'PUT', 'files' => true]) !!}
@include('admin.employees.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}
