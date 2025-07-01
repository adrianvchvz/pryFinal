{!! Form::model($detalle, ['route' => ['admin.scheduledetails.update', $detalle->id], 'method' => 'PUT']) !!}
@include('admin.scheduledetails.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Actualizar</button>
{!! Form::close() !!}
