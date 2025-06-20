{!! Form::model($shift, ['route' => ['admin.shifts.update', $shift], 'method' => 'PUT']) !!}
@include('admin.shifts.template.form')
<button type='submit' class="btn btn-success" style="background-color: #f1f8ec; color: #397044;"> <i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}
