{!! Form::open(['route' => 'admin.routezones.store', 'id' => 'addZoneForm']) !!}
@include('admin.routezones.template.form')
<button type='submit' class="btn btn-success" style="background-color: #f1f8ec; color: #397044;"> <i class="fas fa-cloud-upload-alt"></i>Agregar</button>
{!! Form::close() !!}
