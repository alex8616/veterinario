<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
</head>
<body>

    <h1>Crear cuenta</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('register') }}" method="POST">

        @csrf

        <div>
            <label>Nombre</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label>Correo electrónico</label>
            <input type="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label>Contraseña</label>
            <input type="password" name="password" required>
        </div>

        <div>
            <label>Confirmar contraseña</label>
            <input type="password" name="password_confirmation" required>
        </div>

        <button type="submit">
            Registrarme
        </button>

    </form>

    <p>
        ¿Ya tienes una cuenta?
        <a href="{{ route('login') }}">Iniciar sesión</a>
    </p>

</body>
</html>