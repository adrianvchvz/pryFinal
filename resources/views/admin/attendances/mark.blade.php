<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Marcar Asistencia</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }
        .attendance-container {
            width: 25rem;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 100px
        }
        .logo-container {
            text-align: center;
            margin-bottom: 25px;
        }
        .logo-img {
            max-width: 250px;
            height: auto;
            margin-bottom: 10px;
            margin-top: 130px
        }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #2e7d32;
            margin: 0;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            margin: 15px 0;
            font-size: 14px;
        }
        .checkbox-group input {
            margin-right: 8px;
        }
        .forgot-password {
            text-align: right;
            margin-bottom: 20px;
        }
        .forgot-password a {
            color: #666;
            text-decoration: none;
            font-size: 13px;
        }
        .btn-submit {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 4px;
            background-color: #1f2937;
            color: white;
            font-weight: bold;
            cursor: pointer;
            font-size: 15px;
            text-transform: uppercase;
            margin-bottom: 15px;
        }
        .employee-section {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #666;
        }
        .employee-section a {
            color: #2e7d32;
            text-decoration: none;
            font-weight: bold;
        }
        .alert {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
        }
        .alert-info {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }
        .alert-danger {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: bold;
        }
        .logo-container {
    position: absolute;
    top: 40px;
    text-align: center;
    width: 100%;
}
    </style>
</head>
<body>

    <div class="logo-container">
    <img src="{{ asset('vendor/adminlte/dist/img/EcoPath.jpeg') }}" alt="Eco Path Logo" class="logo-img">

</div>

    <div class="attendance-container">
        
        
        @if (session('status'))
            <div class="alert alert-info">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif
        
        <form method="POST" action="{{ route('attendance.mark') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">DNI</label>
                <input type="text" name="dni" class="form-control" placeholder="Ingrese su DNI" value="{{ old('dni') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" placeholder="Ingrese su contraseña" required>
            </div>
            
            <button type="submit" class="btn-submit">Marcar Asistencia</button>
        </form>
    </div>
</body>
</html>