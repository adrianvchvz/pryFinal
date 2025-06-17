{!! Form::open(['route' => 'admin.contracts.store']) !!}
@include('admin.contracts.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Registrar</button>
{!! Form::close() !!}