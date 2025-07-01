{!! Form::model($schedule, ['route' => ['admin.scheduledays.update', $schedule->id], 'method' => 'PUT']) !!}
@include('admin.scheduledays.template.form')
<button type="submit" class="btn btn-success"><i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}
