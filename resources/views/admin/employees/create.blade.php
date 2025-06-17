{!! Form::open(['route' => 'admin.employees.store', 'files' => true]) !!}
@include('admin.employees.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Registrar</button>
{!! Form::close() !!}
