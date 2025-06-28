{!! Form::model($assignment, ['route' => ['admin.zoneassignments.update', $assignment], 'method' => 'PUT']) !!}
@include('admin.zoneassignments.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}
