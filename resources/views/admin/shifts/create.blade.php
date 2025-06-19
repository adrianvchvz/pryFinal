{!! Form::open(['route' => 'admin.shifts.store']) !!}
@include('admin.shifts.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Registrar</button>
{!! Form::close() !!}
