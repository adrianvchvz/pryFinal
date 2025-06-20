{!! Form::open(['route' => 'admin.employees.store', 'files' => true]) !!}
@include('admin.employees.template.form')
<button type='submit' class="btn btn-success" style="background-color: #f1f8ec; color: #397044;"> <i class="fas fa-cloud-upload-alt"></i>Registrar</button>
{!! Form::close() !!}
