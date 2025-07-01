{!! Form::open(['route' => 'admin.schedules.store']) !!}
@include('admin.schedules.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Registrar</button>
{!! Form::close() !!}
