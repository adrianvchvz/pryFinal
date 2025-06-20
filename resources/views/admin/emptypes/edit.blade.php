{!! Form::model($emptype, ['route' => ['admin.emptypes.update', $emptype], 'method' => 'PUT']) !!}
@include('admin.emptypes.template.form')
<button type='submit' class="btn btn-success" style="background-color: #f1f8ec; color: #397044;"> <i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}
