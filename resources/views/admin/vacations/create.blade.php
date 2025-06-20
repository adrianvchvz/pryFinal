{!! Form::open(['route' => 'admin.vacations.store']) !!}
@include('admin.vacations.template.form')
<button type='submit' class="btn btn-success"  style="background-color: #f1f8ec; color: #397044;"> <i class="fas fa-cloud-upload-alt"></i>Registrar</button>
{!! Form::close() !!}