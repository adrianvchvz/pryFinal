{!! Form::model($zone, ['route' => ['admin.zones.update', $zone], 'method' => 'put']) !!}
@include('admin.zones.template.form')
<button type="submit" class="btn btn-success" style="background-color: #f1f8ec; color: #397044;"><i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}