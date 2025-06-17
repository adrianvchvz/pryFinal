{!! Form::open(['route' => 'admin.brands.store', 'files' => true]) !!}
@include('admin.brands.template.form')
<button type='submit' class="btn btn-success"> <i class="fas fa-cloud-upload-alt"></i>Registrar</button>
{!! Form::close() !!}
