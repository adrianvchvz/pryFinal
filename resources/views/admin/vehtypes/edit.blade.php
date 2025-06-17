{!! Form::model($vehtype, ['route' => ['admin.vehtypes.update', $vehtype], 'method' => 'PUT']) !!}
@include('admin.vehtypes.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}
