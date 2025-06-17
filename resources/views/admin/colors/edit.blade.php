{!! Form::model($color, ['route' => ['admin.colors.update', $color], 'method' => 'PUT']) !!}
@include('admin.colors.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}
