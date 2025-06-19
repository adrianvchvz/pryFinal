{!! Form::open(['route' => 'admin.routezones.store', 'id' => 'addZoneForm']) !!}
@include('admin.routezones.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Agregar</button>
{!! Form::close() !!}
