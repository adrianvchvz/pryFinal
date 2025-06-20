{!! Form::model($schedule, ['route' => ['admin.schedules.update', $schedule], 'method' => 'PUT']) !!}
@include('admin.schedules.template.form', ['scheduledays' => $scheduledays,])
<button type="submit" class="btn btn-success"><i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}
