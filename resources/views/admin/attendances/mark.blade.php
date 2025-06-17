<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Marcar Asistencia</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height: 100vh;">
            <div class="card col-md-6 col-lg-4">
                <div class="card-header text-center" style="background-color: #fff;">
                    <h3>Marca tu Asistencia</h3>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-info">{{ session('status') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">{{ $errors->first() }}</div>
                    @endif
                    <form method="POST" action="{{ route('attendance.mark') }}">
                        @csrf
                        <div class="mb-3">
                            <input type="text" name="dni" class="form-control" placeholder="DNI" value="{{ old('dni') }}">
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Contraseña">
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Marcar Asistencia</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
