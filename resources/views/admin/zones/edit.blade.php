{!! Form::model($zone, ['route' => ['admin.zones.update', $zone], 'method' => 'put']) !!}
@include('admin.zones.template.form')
<button type="submit" class="btn btn-success"><i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}