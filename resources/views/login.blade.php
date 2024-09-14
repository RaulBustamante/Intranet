<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ariel - Hub | Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ecf0f1;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }

        .login-header {
            margin-bottom: 1.5rem;
        }

        .login-header img {
            width: 80px; /* Tamaño más pequeño del logo */
            height: auto;
            margin-bottom: 1rem;
        }

        .login-header h1 {
            font-size: 1.8rem;
            color: #2c3e50;
            margin: 0;
        }

        .login-header hr {
            width: 50%;
            border-top: 2px solid #e74c3c;
            margin: 1rem auto;
        }

        .form-group label {
            color: #34495e;
        }

        .btn-login {
            background-color: #e74c3c;
            color: #fff;
            width: 100%;
            border: none;
            padding: 0.8rem;
            border-radius: 5px;
            font-size: 1rem;
            transition: background-color 0.3s ease;
            margin-bottom: 1rem;
        }

        .btn-login:hover {
            background-color: #c0392b;
        }

        .btn-logout {
            background-color: #7f8c8d;
            color: #fff;
            width: 100%;
            border: none;
            padding: 0.8rem;
            border-radius: 5px;
            font-size: 1rem;
        }

        .footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .footer a {
            color: #3498db;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="{{ asset('img/company-logo.png') }}" alt="Ariel Logo">
            <h1>Ariel Hub</h1>
            <hr>
        </div>

        <form action="{{ route('login.post') }}" method="post">
            @csrf
            <!-- Campo de usuario -->
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Ingresa tu usuario" required>
            </div>
            <!-- Campo de contraseña -->
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña" required>
            </div>
            <!-- Botón para iniciar sesión -->
            <button type="submit" class="btn btn-login">Ingresar</button>
        </form>

        @if($errors->has('login_error'))
            <div class="alert alert-danger mt-2">
                {{ $errors->first('login_error') }}
            </div>
        @endif

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-logout">Cerrar sesión</button>
        </form>

        <div class="footer">
            <p>&copy; 2024 Intranet contact: <a href="mailto:raulb@arielpremium.com">raulb@arielpremium.com</a></p>
        </div>
    </div>
</body>
</html>
