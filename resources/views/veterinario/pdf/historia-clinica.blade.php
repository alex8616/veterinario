<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historia Clínica - {{ $mascota->nombre }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DejaVu Sans','Helvetica',sans-serif;font-size:12px;color:#1e293b;background:#f8fafc;padding:30px 35px;line-height:1.5}
        .document{max-width:1100px;margin:0 auto;background:#fff;padding:30px 35px;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.05)}
        .header{text-align:center;padding-bottom:18px;margin-bottom:25px;border-bottom:3px solid #c9a84c;position:relative}
        .header h1{font-size:24px;font-weight:600;color:#1a2a4a;letter-spacing:2px;text-transform:uppercase;margin:0}
        .header h1 span{color:#c9a84c}
        .header p{font-size:13px;color:#64748b;margin-top:4px;font-weight:300;letter-spacing:0.3px}
        .seccion{margin-top:22px;margin-bottom:18px}
        .seccion-titulo{background:linear-gradient(135deg,#1a2a4a,#2a3f6a);color:#fff;padding:8px 16px;font-size:13px;font-weight:600;letter-spacing:0.5px;border-radius:6px 6px 0 0;text-transform:uppercase}
        .datos{width:100%;border-collapse:collapse;border-radius:0 0 6px 6px;overflow:hidden}
        .datos td{padding:7px 12px;border-bottom:1px solid #e9edf2;vertical-align:middle}
        .datos tr:last-child td{border-bottom:none}
        .datos .label{font-weight:600;color:#1a2a4a;width:18%;background:#f1f5f9}
        .datos .value{font-weight:400;color:#1e293b}
        table{width:100%;border-collapse:collapse;margin-top:0;font-size:11.5px;border-radius:0 0 6px 6px;overflow:hidden}
        th{background:#1a2a4a;color:#fff;font-weight:600;padding:7px 10px;text-align:left;font-size:10.5px;text-transform:uppercase;letter-spacing:0.3px;border:none}
        td{padding:6px 10px;border-bottom:1px solid #e9edf2;vertical-align:top;background:#fff}
        tr:nth-child(even) td{background:#f8fafc}
        tr:last-child td{border-bottom:none}
        .sin-registros{padding:14px;text-align:center;color:#94a3b8;font-style:italic;background:#f8fafc;border-radius:0 0 6px 6px;border:1px solid #e2e8f0;border-top:none}
        .estado{font-weight:600;text-transform:uppercase;font-size:10px;padding:2px 12px;border-radius:30px;display:inline-block;color:#fff}
        .estado.activo{background:#0f766e}
        .estado.finalizado{background:#b91c1c}
        .estado.pendiente{background:#b45309}
        .estado.cancelado{background:#6b7280}
        .estado.default{background:#6b7280}
        .observaciones-box{padding:12px 16px;border:1px solid #e2e8f0;border-radius:0 0 6px 6px;background:#fafcfc;color:#1e293b;font-size:12px;line-height:1.6}
        .footer{margin-top:28px;padding-top:14px;border-top:2px solid #e2e8f0;text-align:center;color:#94a3b8;font-size:10.5px;letter-spacing:0.2px}
        .footer strong{color:#1a2a4a;font-weight:600}
        .page-break{page-break-after:always;border:none;margin:0;height:1px}
        @media print{body{background:#fff;padding:15px}.document{box-shadow:none;padding:15px 20px}}
    </style>
</head>
<body>
<div class="document">
    <div class="header">
        <h1>Historia <span>Clínica</span> Veterinaria</h1>
        <p>Registro médico de la mascota</p>
    </div>
    <div class="seccion">
        <div class="seccion-titulo">Datos de la mascota</div>
        <table class="datos">
            <tr><td class="label">Nombre</td><td class="value">{{ $mascota->nombre }}</td><td class="label">Especie</td><td class="value">{{ $mascota->especie ?? 'No registrado' }}</td></tr>
            <tr><td class="label">Raza</td><td class="value">{{ $mascota->raza ?? 'No registrada' }}</td><td class="label">Sexo</td><td class="value">{{ $mascota->sexo ?? 'No registrado' }}</td></tr>
            <tr><td class="label">Fecha nacimiento</td><td class="value">{{ $mascota->fecha_nacimiento ? \Carbon\Carbon::parse($mascota->fecha_nacimiento)->format('d/m/Y') : 'No registrada' }}</td><td class="label">Peso</td><td class="value">{{ $mascota->peso ? $mascota->peso.' kg' : 'No registrado' }}</td></tr>
            <tr><td class="label">Color</td><td class="value" colspan="3">{{ $mascota->color ?? 'No registrado' }}</td></tr>
        </table>
    </div>
    <div class="seccion">
        <div class="seccion-titulo">Propietario</div>
        <table class="datos">
            <tr><td class="label">Nombre</td><td class="value">{{ $mascota->user->name ?? 'No registrado' }}</td></tr>
            <tr><td class="label">Correo</td><td class="value">{{ $mascota->user->email ?? 'No registrado' }}</td></tr>
        </table>
    </div>
    <div class="seccion">
        <div class="seccion-titulo">Consultas</div>
        @if($mascota->consultas->isEmpty())
            <div class="sin-registros">No existen consultas registradas.</div>
        @else
            <table>
                <thead><tr><th>Fecha</th><th>Motivo</th><th>Diagnóstico</th><th>Peso</th><th>Temperatura</th><th>Veterinario</th></tr></thead>
                <tbody>
                    @foreach($mascota->consultas as $consulta)
                        <tr>
                            <td>{{ $consulta->fecha ? \Carbon\Carbon::parse($consulta->fecha)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $consulta->motivo ?? '-' }}</td>
                            <td>{{ $consulta->diagnostico ?? '-' }}</td>
                            <td>{{ $consulta->peso ? $consulta->peso.' kg' : '-' }}</td>
                            <td>{{ $consulta->temperatura ? $consulta->temperatura.' °C' : '-' }}</td>
                            <td>{{ $consulta->veterinario->name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="seccion">
        <div class="seccion-titulo">Vacunas</div>
        @if($mascota->vacunas->isEmpty())
            <div class="sin-registros">No existen vacunas registradas.</div>
        @else
            <table>
                <thead><tr><th>Vacuna</th><th>Fecha aplicación</th><th>Próxima dosis</th><th>Veterinario</th><th>Observaciones</th></tr></thead>
                <tbody>
                    @foreach($mascota->vacunas as $vacuna)
                        <tr>
                            <td>{{ $vacuna->nombre }}</td>
                            <td>{{ $vacuna->fecha_aplicacion ? \Carbon\Carbon::parse($vacuna->fecha_aplicacion)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $vacuna->proxima_dosis ? \Carbon\Carbon::parse($vacuna->proxima_dosis)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $vacuna->veterinario->name ?? '-' }}</td>
                            <td>{{ $vacuna->observaciones ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="seccion">
        <div class="seccion-titulo">Desparasitaciones</div>
        @if($mascota->desparasitaciones->isEmpty())
            <div class="sin-registros">No existen desparasitaciones registradas.</div>
        @else
            <table>
                <thead><tr><th>Producto</th><th>Fecha</th><th>Próxima fecha</th><th>Veterinario</th><th>Observaciones</th></tr></thead>
                <tbody>
                    @foreach($mascota->desparasitaciones as $desparasitacion)
                        <tr>
                            <td>{{ $desparasitacion->producto }}</td>
                            <td>{{ $desparasitacion->fecha ? \Carbon\Carbon::parse($desparasitacion->fecha)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $desparasitacion->proxima_fecha ? \Carbon\Carbon::parse($desparasitacion->proxima_fecha)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $desparasitacion->veterinario->name ?? '-' }}</td>
                            <td>{{ $desparasitacion->observaciones ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="seccion">
        <div class="seccion-titulo">Tratamientos</div>
        @if($mascota->tratamientos->isEmpty())
            <div class="sin-registros">No existen tratamientos registrados.</div>
        @else
            <table>
                <thead><tr><th>Tratamiento</th><th>Inicio</th><th>Fin</th><th>Estado</th><th>Veterinario</th></tr></thead>
                <tbody>
                    @foreach($mascota->tratamientos as $tratamiento)
                        @php
                            $estado = strtolower($tratamiento->estado ?? '');
                            $claseEstado = match($estado) {
                                'activo' => 'activo',
                                'finalizado' => 'finalizado',
                                'pendiente' => 'pendiente',
                                'cancelado' => 'cancelado',
                                default => 'default'
                            };
                        @endphp
                        <tr>
                            <td><strong>{{ $tratamiento->nombre }}</strong>@if($tratamiento->descripcion)<br>{{ $tratamiento->descripcion }}@endif</td>
                            <td>{{ $tratamiento->fecha_inicio ? \Carbon\Carbon::parse($tratamiento->fecha_inicio)->format('d/m/Y') : '-' }}</td>
                            <td>{{ $tratamiento->fecha_fin ? \Carbon\Carbon::parse($tratamiento->fecha_fin)->format('d/m/Y') : '-' }}</td>
                            <td><span class="estado {{ $claseEstado }}">{{ $tratamiento->estado ?? 'Sin estado' }}</span></td>
                            <td>{{ $tratamiento->veterinario->name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    @if($mascota->observaciones)
        <div class="seccion">
            <div class="seccion-titulo">Observaciones generales</div>
            <div class="observaciones-box">{{ $mascota->observaciones }}</div>
        </div>
    @endif
    <div class="footer">
        <strong>Historia clínica veterinaria</strong> — {{ $mascota->nombre }} — Generada el {{ now()->format('d/m/Y H:i') }}
    </div>
</div>
</body>
</html>