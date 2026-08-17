<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Mi Panel - Veterinaria</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6fa;
        }

        .navbar {
            background: #198754;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            margin: 0;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logout {
            background: white;
            color: #198754;
            border: none;
            padding: 8px 14px;
            border-radius: 5px;
            cursor: pointer;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .welcome {
            margin-bottom: 25px;
        }

        .welcome h1 {
            margin-bottom: 5px;
        }

        .modules {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
        }

        .card h3 {
            margin-top: 0;
        }

        .card p {
            color: #666;
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
            color: #198754;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <nav class="navbar">

        <h2>🐾 Veterinaria</h2>

        <div class="user-info">

            <span>
                {{ auth()->user()->name }}
            </span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="logout">
                    Cerrar sesión
                </button>
            </form>

        </div>

    </nav>


    <main class="container">

        <div class="welcome">

            <h1>
                Bienvenido, {{ auth()->user()->name }}
            </h1>

            <p>
                Desde aquí puedes administrar la información de tus mascotas.
            </p>

        </div>


        <div class="modules">

            <!-- Perfil -->
            <div class="card">

                <h3>👤 Mi perfil</h3>

                <p>
                    Consulta y actualiza tus datos personales.
                </p>

                <a href="#">
                    Ver mi perfil
                </a>

            </div>


            <!-- Mascotas -->
            <div class="card">

                <h3>🐶 Mis mascotas</h3>

                <p>
                    Registra y consulta tus mascotas.
                </p>

                <a href="#">
                    Ver mascotas
                </a>

            </div>


            <!-- Citas -->
            <div class="card">

                <h3>📅 Mis citas</h3>

                <p>
                    Consulta tus próximas citas veterinarias.
                </p>

                <a href="#">
                    Ver citas
                </a>

            </div>


            <!-- Historia -->
            <div class="card">

                <h3>🩺 Historia clínica</h3>

                <p>
                    Consulta el historial médico de tus mascotas.
                </p>

                <a href="#">
                    Ver historial
                </a>

            </div>


            <!-- Vacunas -->
            <div class="card">

                <h3>💉 Vacunas</h3>

                <p>
                    Consulta las vacunas registradas.
                </p>

                <a href="#">
                    Ver vacunas
                </a>

            </div>


            <!-- Tratamientos -->
            <div class="card">

                <h3>💊 Tratamientos</h3>

                <p>
                    Consulta los tratamientos de tus mascotas.
                </p>

                <a href="#">
                    Ver tratamientos
                </a>

            </div>


            <!-- Noticias -->
            <div class="card">

                <h3>📰 Noticias</h3>

                <p>
                    Lee las últimas noticias de la veterinaria.
                </p>

                <a href="#">
                    Ver noticias
                </a>

            </div>

        </div>

    </main>

</body>
</html>