{!! Form::model($color, ['route' => ['admin.colors.update', $color], 'method' => 'PUT']) !!}
@include('admin.colors.template.form')
<button type='submit' class="btn btn-success" style="background-color: #f1f8ec; color: #397044;"> <i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}
