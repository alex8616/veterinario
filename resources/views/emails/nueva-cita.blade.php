<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <title>Nueva cita veterinaria</title>
</head>

<body style="
    margin: 0;
    padding: 0;
    background-color: #f4f6f8;
    font-family: Arial, Helvetica, sans-serif;
">

    <div style="
        max-width: 600px;
        margin: 30px auto;
        background: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    ">

        {{-- Encabezado --}}
        <div style="
            background-color: #151f2c;
            color: white;
            padding: 25px;
            text-align: center;
        ">

            <h1 style="margin: 0;">
                Nueva cita veterinaria
            </h1>

            <p style="
                margin: 8px 0 0;
                opacity: 0.8;
            ">
                Se ha registrado una nueva cita
            </p>

        </div>


        {{-- Contenido --}}
        <div style="padding: 30px;">

            <p>
                Hola,
            </p>

            <p>
                Se ha registrado una nueva cita para usted.
            </p>


            <div style="
                background-color: #f8f9fa;
                border-radius: 8px;
                padding: 20px;
                margin: 20px 0;
            ">

                <h3 style="
                    margin-top: 0;
                    color: #151f2c;
                ">
                    Información de la cita
                </h3>


                <p>
                    <strong>Mascota:</strong>
                    {{ $cita->mascota->nombre }}
                </p>

                <p>
                    <strong>Fecha:</strong>
                    {{ $cita->fecha->format('d/m/Y') }}
                </p>

                <p>
                    <strong>Turno:</strong>
                    {{ ucfirst($cita->turno) }}
                </p>

                <p>
                    <strong>Hora:</strong>
                    {{ substr($cita->hora, 0, 5) }}
                </p>

                <p>
                    <strong>Motivo:</strong>
                    {{ $cita->motivo }}
                </p>

                @if($cita->observaciones)

                    <p>
                        <strong>Observaciones:</strong><br>

                        {{ $cita->observaciones }}
                    </p>

                @endif

                <p>
                    <strong>Estado:</strong>

                    <span style="
                        color: #856404;
                        background-color: #fff3cd;
                        padding: 4px 8px;
                        border-radius: 4px;
                    ">
                        {{ ucfirst($cita->estado) }}
                    </span>

                </p>

            </div>


            <p>
                Por favor, ingrese al sistema para consultar
                los detalles de la cita.
            </p>

            <p style="margin-top: 30px;">
                Saludos,<br>
                <strong>Veterinaria</strong>
            </p>

        </div>


        {{-- Footer --}}
        <div style="
            background-color: #f8f9fa;
            padding: 15px;
            text-align: center;
            color: #777;
            font-size: 12px;
        ">

            Este correo fue generado automáticamente por el sistema.

        </div>

    </div>

</body>

</html>