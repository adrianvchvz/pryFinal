{!! Form::model($vehicle, ['route' => ['admin.vehicles.update', $vehicle], 'method' => 'PUT', 'files' => true]) !!}
@include('admin.vehicles.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}
