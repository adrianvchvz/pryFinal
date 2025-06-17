{!! Form::open(['route' => 'admin.vehicles.store', 'files' => true]) !!}
@include('admin.vehicles.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Registrar</button>
{!! Form::close() !!}
