{!! Form::model($assignment, ['route' => ['admin.zoneassignments.update', $assignment], 'method' => 'PUT']) !!}
@include('admin.zoneassignments.template.form')
<button type='submit' class="btn btn-success" style="background-color: #f1f8ec; color: #397044;"> <i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}
