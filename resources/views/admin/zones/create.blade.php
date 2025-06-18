{!! Form::open(['route' => 'admin.zones.store']) !!}
@include('admin.zones.template.form')
<button type="submit" class="btn btn-success"><i class="fas fa-cloud-upload-alt"></i>Registrar</button>
{!! Form::close() !!}