{!! Form::open(['route' => 'admin.vacations.store']) !!}
@include('admin.vacations.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Registrar</button>
{!! Form::close() !!}