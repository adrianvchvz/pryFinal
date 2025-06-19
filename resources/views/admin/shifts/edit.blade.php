{!! Form::model($shift, ['route' => ['admin.shifts.update', $shift], 'method' => 'PUT']) !!}
@include('admin.shifts.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}
