{!! Form::model($emptype, ['route' => ['admin.emptypes.update', $emptype], 'method' => 'PUT']) !!}
@include('admin.emptypes.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}
