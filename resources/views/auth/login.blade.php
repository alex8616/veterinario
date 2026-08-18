<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | Veterinaria</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: #f4f8f6;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .login-card {
            background: white;
            padding: 35px;
            border-radius: 15px;

            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo-icon {
            font-size: 55px;
        }

        .logo h1 {
            margin: 10px 0 5px;
            color: #198754;
        }

        .logo p {
            margin: 0;
            color: #777;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;

            border: 1px solid #ddd;
            border-radius: 8px;

            font-size: 15px;
        }

        input:focus {
            outline: none;
            border-color: #198754;
        }

        .error {
            background: #f8d7da;
            color: #842029;

            padding: 10px;
            border-radius: 8px;

            margin-bottom: 20px;
        }

        .btn-login {
            width: 100%;
            padding: 12px;

            border: none;
            border-radius: 8px;

            background: #198754;
            color: white;

            font-size: 16px;
            font-weight: bold;

            cursor: pointer;
        }

        .btn-login:hover {
            background: #157347;
        }

        .register {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .register a {
            color: #198754;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="login-container">

    <div class="login-card">

        <div class="logo">

            <div class="logo-icon">
                🐾
            </div>

            <h1>Veterinaria</h1>

            <p>Inicia sesión en tu cuenta</p>

        </div>


        @if ($errors->any())

            <div class="error">

                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach

            </div>

        @endif


        <form action="{{ route('login') }}" method="POST">

            @csrf


            <div class="form-group">

                <label for="email">
                    Correo electrónico
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >

            </div>


            <div class="form-group">

                <label for="password">
                    Contraseña
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                >

            </div>


            <button type="submit" class="btn-login">
                Iniciar sesión
            </button>

        </form>


        <div class="register">

            ¿No tienes una cuenta?

            <a href="{{ route('register') }}">
                Crear cuenta
            </a>

        </div>

    </div>

</div>

</body>
</html>