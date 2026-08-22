<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Historial Clínico</title>
    <style>
        /* ===== RESET Y CONFIGURACIÓN ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            background: #ffffff;
            color: #1e293b;
            padding: 12px 30px 20px 30px;
            line-height: 1.4;
        }
        .document { max-width: 1100px; margin: 0 auto; }

        /* ===== CABECERA ===== */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 16px 6px 16px;
            margin-bottom: 12px;
            border-bottom: 3px solid #c9a84c;
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
            border-radius: 6px 6px 0 0;
        }
        .header-title { flex: 1; text-align: center; }
        .header-title h1 {
            font-size: 22px;
            font-weight: 600;
            letter-spacing: 1.5px;
            color: #1a2a4a;
            text-transform: uppercase;
            margin: 0;
        }
        .header-title h1 span { color: #c9a84c; }
        .header-title small {
            font-size: 11px;
            color: #64748b;
            font-weight: 300;
            display: block;
            margin-top: 1px;
        }

        /* ===== PROPIETARIO ===== */
        .owner-info {
            display: flex;
            flex-wrap: wrap;
            gap: 2px 30px;
            margin-bottom: 10px;
            padding: 6px 14px;
            background: #f1f5f9;
            border-radius: 4px;
            border-left: 4px solid #c9a84c;
        }
        .owner-info p {
            margin: 0;
            font-size: 12.5px;
            color: #1e293b;
        }
        .owner-info strong {
            font-weight: 600;
            color: #0f172a;
        }

        /* ===== CABECERA DE MASCOTAS ===== */
        .mascotas-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin: 16px 0 10px 0;
            padding-bottom: 4px;
            border-bottom: 2px solid #c9a84c;
        }
        .mascotas-header h2 {
            font-size: 18px;
            font-weight: 600;
            color: #1a2a4a;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .mascotas-header .total {
            font-size: 13px;
            color: #64748b;
            font-weight: 400;
            background: #eef2ff;
            padding: 0 12px;
            border-radius: 20px;
        }

        /* ===== ALTERNATIVA A ===== */
        .pet-header-centered {
            text-align: center;
            margin-bottom: 4px;
        }
        .pet-header-centered .pet-name {
            font-size: 24px;
            font-weight: 600;
            color: #1a2a4a;
            letter-spacing: 1px;
            display: inline-block;
            border-bottom: 3px solid #c9a84c;
            padding-bottom: 2px;
            padding-left: 8px;
            padding-right: 8px;
        }
        .pet-details-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 6px 20px;
            padding: 6px 16px;
            background: #f8fafc;
            border-radius: 30px;
            border: 1px solid #e2e8f0;
            font-size: 12.5px;
            color: #1e293b;
            margin-top: 4px;
        }
        .pet-details-row .item {
            display: inline-block;
        }
        .pet-details-row .item strong {
            font-weight: 600;
            color: #1a2a4a;
            margin-right: 2px;
        }
        .pet-details-row .separator {
            color: #c9a84c;
            font-weight: 300;
        }

        /* ===== SECCIONES Y TABLAS ===== */
        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #1a2a4a;
            margin: 10px 0 2px 0;
            letter-spacing: 0.2px;
            border-bottom: 1px dashed #cbd5e1;
            padding-bottom: 1px;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
        }
        .section-title .count {
            font-weight: 400;
            color: #64748b;
            font-size: 11px;
            background: #eef2ff;
            padding: 0 10px;
            border-radius: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11.5px;
            margin: 0 0 4px 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
            border-radius: 4px;
            overflow: hidden;
        }
        table th {
            background: #1a2a4a;
            color: #ffffff;
            font-weight: 600;
            padding: 4px 8px 4px 0;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: none;
        }
        table td {
            padding: 3px 8px 3px 0;
            border-bottom: 1px solid #e9edf2;
            vertical-align: top;
            background: #ffffff;
        }
        table tr:nth-child(even) td { background: #f8fafc; }
        table tr:last-child td { border-bottom: none; }

        .status-label {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.2px;
            padding: 1px 10px;
            border-radius: 30px;
            display: inline-block;
            color: #ffffff;
        }
        .status-activo     { background: #0f766e; }
        .status-finalizado { background: #b91c1c; }
        .status-pendiente  { background: #b45309; }
        .status-cancelado  { background: #6b7280; }
        .status-default    { background: #6b7280; }

        .empty-message {
            color: #94a3b8;
            font-style: italic;
            font-size: 11.5px;
            background: #f8fafc;
            padding: 2px 12px;
            border-radius: 4px;
        }
        .page-break {
            page-break-after: always;
            border: none;
            margin: 0;
            height: 1px;
        }
        .footer {
            font-size: 10px;
            color: #94a3b8;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            margin-top: 10px;
            font-weight: 300;
            letter-spacing: 0.2px;
            font-family: 'Helvetica', 'Arial', sans-serif;
        }
        .footer strong { color: #1a2a4a; }
    </style>
</head>
<body>
<div class="document">

    {{-- CABECERA --}}
    <div class="header">
        <div class="header-title">
            <h1>Historial <span>Clínico</span></h1>
            <small>Generado el {{ date('d/m/Y H:i') }}</small>
        </div>
    </div>

    {{-- PROPIETARIO --}}
    <div class="owner-info">
        <p><strong>Propietario:</strong> {{ $cliente->name }}</p>
        <p><strong>Correo:</strong> {{ $cliente->email }}</p>
        <p><strong>Teléfono:</strong> {{ $cliente->phone ?? 'No registrado' }}</p>
        <p><strong>Documento:</strong> {{ $cliente->document ?? 'N/A' }}</p>
    </div>

    {{-- CABECERA MASCOTAS --}}
    <div class="mascotas-header">
        <h2>Mascotas</h2>
        <span class="total">{{ $cliente->mascotas->count() }} registradas</span>
    </div>

    {{-- RECORRER MASCOTAS --}}
    @foreach($cliente->mascotas as $mascota)
        <div class="pet-section">

            {{-- ALTERNATIVA A --}}
            <div class="pet-header-centered">
                <div class="pet-name">{{ $mascota->nombre }}</div>
            </div>
            <div class="pet-details-row">
                <span class="item"><strong>Especie:</strong> {{ $mascota->especie ?? 'N/E' }}</span>
                <span class="separator">·</span>
                <span class="item"><strong>Raza:</strong> {{ $mascota->raza ?? 'No especificada' }}</span>
                <span class="separator">·</span>
                <span class="item"><strong>Sexo:</strong> {{ $mascota->sexo ?? 'N/E' }}</span>
                <span class="separator">·</span>
                <span class="item"><strong>Peso:</strong> {{ $mascota->peso ? $mascota->peso.' kg' : 'N/E' }}</span>
                <span class="separator">·</span>
                <span class="item"><strong>Color:</strong> {{ $mascota->color ?? 'N/E' }}</span>
                <span class="separator">·</span>
                <span class="item"><strong>Nacimiento:</strong> {{ $mascota->fecha_nacimiento ? \Carbon\Carbon::parse($mascota->fecha_nacimiento)->format('d/m/Y') : 'N/E' }}</span>
            </div>

            {{-- CONSULTAS --}}
            <div class="section-title"><span>Consultas</span><span class="count">{{ $mascota->consultas->count() }}</span></div>
            @if($mascota->consultas->isEmpty())
                <div class="empty-message">No hay consultas registradas.</div>
            @else
                <table>
                    <thead><tr><th style="width:14%;">Fecha</th><th style="width:24%;">Motivo</th><th style="width:30%;">Diagnóstico</th><th style="width:32%;">Observaciones</th></tr></thead>
                    <tbody>
                        @foreach($mascota->consultas as $consulta)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($consulta->fecha)->format('d/m/Y') }}</td>
                                <td>{{ $consulta->motivo ?? '--' }}</td>
                                <td>{{ $consulta->diagnostico ?? 'Sin diagnóstico' }}</td>
                                <td>{{ $consulta->observaciones ?? '--' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- VACUNAS --}}
            <div class="section-title"><span>Vacunas</span><span class="count">{{ $mascota->vacunas->count() }}</span></div>
            @if($mascota->vacunas->isEmpty())
                <div class="empty-message">No hay vacunas registradas.</div>
            @else
                <table>
                    <thead><tr><th style="width:22%;">Nombre</th><th style="width:20%;">Aplicación</th><th style="width:20%;">Próxima dosis</th><th style="width:38%;">Observaciones</th></tr></thead>
                    <tbody>
                        @foreach($mascota->vacunas as $vacuna)
                            <tr>
                                <td><strong>{{ $vacuna->nombre }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($vacuna->fecha_aplicacion)->format('d/m/Y') }}</td>
                                <td>{{ $vacuna->proxima_dosis ? \Carbon\Carbon::parse($vacuna->proxima_dosis)->format('d/m/Y') : 'No programada' }}</td>
                                <td>{{ $vacuna->observaciones ?? '--' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- DESPARASITACIONES --}}
            <div class="section-title"><span>Desparasitaciones</span><span class="count">{{ $mascota->desparasitaciones->count() }}</span></div>
            @if($mascota->desparasitaciones->isEmpty())
                <div class="empty-message">No hay desparasitaciones registradas.</div>
            @else
                <table>
                    <thead><tr><th style="width:22%;">Producto</th><th style="width:20%;">Fecha</th><th style="width:20%;">Próxima fecha</th><th style="width:38%;">Observaciones</th></tr></thead>
                    <tbody>
                        @foreach($mascota->desparasitaciones as $desparasitacion)
                            <tr>
                                <td><strong>{{ $desparasitacion->producto }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($desparasitacion->fecha)->format('d/m/Y') }}</td>
                                <td>{{ $desparasitacion->proxima_fecha ? \Carbon\Carbon::parse($desparasitacion->proxima_fecha)->format('d/m/Y') : 'No programada' }}</td>
                                <td>{{ $desparasitacion->observaciones ?? '--' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- TRATAMIENTOS --}}
            <div class="section-title"><span>Tratamientos</span><span class="count">{{ $mascota->tratamientos->count() }}</span></div>
            @if($mascota->tratamientos->isEmpty())
                <div class="empty-message">No hay tratamientos registrados.</div>
            @else
                <table>
                    <thead><tr><th style="width:18%;">Nombre</th><th style="width:16%;">Inicio</th><th style="width:16%;">Fin</th><th style="width:18%;">Estado</th><th style="width:32%;">Observaciones</th></tr></thead>
                    <tbody>
                        @foreach($mascota->tratamientos as $tratamiento)
                            @php
                                $estado = strtolower($tratamiento->estado ?? '');
                                $claseEstado = match($estado) {
                                    'activo'     => 'status-activo',
                                    'finalizado' => 'status-finalizado',
                                    'pendiente'  => 'status-pendiente',
                                    'cancelado'  => 'status-cancelado',
                                    default      => 'status-default'
                                };
                            @endphp
                            <tr>
                                <td><strong>{{ $tratamiento->nombre }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($tratamiento->fecha_inicio)->format('d/m/Y') }}</td>
                                <td>{{ $tratamiento->fecha_fin ? \Carbon\Carbon::parse($tratamiento->fecha_fin)->format('d/m/Y') : '--' }}</td>
                                <td><span class="status-label {{ $claseEstado }}">{{ $tratamiento->estado ?? 'Sin estado' }}</span></td>
                                <td>{{ $tratamiento->observaciones ?? '--' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div> {{-- pet-section --}}

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif

    @endforeach

    {{-- FOOTER --}}
    <div class="footer">
        <strong>Historial clínico</strong> · Generado automáticamente · Página {PAGE_NUM} de {PAGE_COUNT}
    </div>

</div>
</body>
</html>