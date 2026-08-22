@extends('layouts.my-dashboard-layout')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    :root {
        --primary: #4f46e5;
        --primary-light: #818cf8;
        --primary-dark: #3730a3;
        --accent: #c9a84c;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-500: #64748b;
        --gray-700: #334155;
        --gray-900: #0f172a;
        --shadow-sm: 0 2px 8px rgba(79, 70, 229, 0.06);
        --shadow-md: 0 8px 30px rgba(79, 70, 229, 0.10);
        --shadow-lg: 0 15px 50px rgba(79, 70, 229, 0.12);
        --radius: 16px;
        --radius-sm: 10px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: #f1f5f9;
    }

    .page-header {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: var(--radius);
        padding: 1.5rem 2rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid rgba(255,255,255,0.6);
        backdrop-filter: blur(4px);
    }

    .page-header .avatar-pet {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        font-size: 1.2rem;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        transition: var(--transition);
        letter-spacing: 1px;
    }

    .page-header .avatar-pet:hover {
        transform: scale(1.05) rotate(-3deg);
    }

    .page-header h2 {
        font-weight: 700;
        font-size: 1.75rem;
        letter-spacing: -0.02em;
        color: var(--gray-900);
    }

    .page-header p {
        color: var(--gray-500);
        font-weight: 400;
        font-size: 0.95rem;
    }

    .card-modern {
        border: none;
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        overflow: hidden;
    }

    .card-modern:hover {
        box-shadow: var(--shadow-md);
    }

    .card-modern .card-body {
        padding: 1.75rem;
    }

    .input-group-modern .input-group-text {
        background: white;
        border: 2px solid var(--gray-200);
        border-right: none;
        border-radius: var(--radius-sm) 0 0 var(--radius-sm);
        color: var(--gray-500);
        padding: 0.6rem 1rem;
        transition: var(--transition);
        font-weight: 500;
        font-size: 0.85rem;
    }

    .input-group-modern .form-control {
        border: 2px solid var(--gray-200);
        border-left: none;
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        font-family: 'Inter', sans-serif;
        transition: var(--transition);
    }

    .input-group-modern .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.10);
    }

    .table-modern {
        border-collapse: separate;
        border-spacing: 0 0.4rem;
    }

    .table-modern thead th {
        background: var(--gray-50);
        color: var(--gray-700);
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 0.8rem 1rem;
        border: none;
        border-radius: var(--radius-sm) var(--radius-sm) 0 0;
    }

    .table-modern tbody tr {
        background: white;
        border-radius: var(--radius-sm);
        box-shadow: 0 1px 4px rgba(0,0,0,0.02);
        transition: var(--transition);
    }

    .table-modern tbody tr:hover {
        box-shadow: var(--shadow-sm);
        transform: translateY(-2px);
    }

    .table-modern tbody td {
        padding: 0.9rem 1rem;
        border: none;
        vertical-align: middle;
        background: white;
    }

    .table-modern tbody td:first-child {
        border-radius: var(--radius-sm) 0 0 var(--radius-sm);
    }

    .table-modern tbody td:last-child {
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
    }

    .badge-modern {
        padding: 0.4rem 0.9rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        letter-spacing: 0.02em;
    }

    .badge-primary-modern {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.25);
    }

    .badge-success-modern {
        background: #10b981;
        color: white;
    }

    .badge-warning-modern {
        background: #f59e0b;
        color: #1e293b;
    }

    .badge-danger-modern {
        background: #ef4444;
        color: white;
    }

    .btn-outline-modern {
        border: 2px solid var(--gray-200);
        border-radius: var(--radius-sm);
        padding: 0.4rem 1.2rem;
        font-weight: 500;
        color: var(--gray-700);
        transition: var(--transition);
        background: transparent;
        font-size: 0.85rem;
    }

    .btn-outline-modern:hover {
        border-color: var(--primary);
        background: var(--gray-50);
        color: var(--primary);
        transform: translateY(-1px);
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border: none;
        border-radius: var(--radius-sm);
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        color: white;
        transition: var(--transition);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.30);
        font-size: 0.85rem;
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(79, 70, 229, 0.40);
        color: white;
    }

    .mascota-selector {
        border: 2px solid var(--gray-200) !important;
        border-radius: var(--radius-sm) !important;
        transition: var(--transition);
        background: white;
        cursor: pointer;
        padding: 0.8rem 1rem !important;
    }

    .mascota-selector:hover {
        border-color: var(--primary-light) !important;
        background: var(--gray-50) !important;
        transform: translateX(4px);
    }

    .mascota-selector.active {
        border-color: var(--primary) !important;
        background: #eef2ff !important;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08);
    }

    .mascota-selector .avatar-pet {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
        font-size: 1rem;
        font-weight: 600;
        flex-shrink: 0;
        transition: var(--transition);
    }

    .mascota-selector:hover .avatar-pet {
        transform: scale(1.05);
    }

    .mascota-selector .chevron {
        color: var(--gray-300);
        transition: var(--transition);
        font-size: 1.2rem;
        font-weight: 300;
    }

    .mascota-selector:hover .chevron {
        color: var(--primary);
        transform: translateX(4px);
    }

    .detalle-historia {
        border-radius: var(--radius-sm);
        border: 2px solid var(--gray-200);
        background: white;
        transition: var(--transition);
        min-height: 300px;
    }

    .detalle-historia .card {
        border: none;
        background: transparent;
    }

    .detalle-historia .card-body {
        padding: 1.5rem;
    }

    .historia-seccion {
        background: white;
        border-radius: var(--radius-sm);
        padding: 1.25rem;
        margin-bottom: 1.25rem;
        border: 1px solid var(--gray-200);
        transition: var(--transition);
    }

    .historia-seccion:hover {
        border-color: var(--primary-light);
        box-shadow: var(--shadow-sm);
    }

    .historia-seccion .seccion-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px dashed var(--gray-200);
    }

    .historia-seccion .seccion-header h6 {
        font-weight: 600;
        color: var(--gray-900);
        margin: 0;
        font-size: 0.95rem;
    }

    .historia-item {
        border: 1px solid var(--gray-100);
        border-radius: var(--radius-sm);
        padding: 1rem;
        margin-bottom: 0.75rem;
        background: var(--gray-50);
        transition: var(--transition);
    }

    .historia-item:hover {
        background: white;
        border-color: var(--gray-300);
    }

    .historia-item .badge {
        font-weight: 500;
    }

    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
        color: var(--gray-500);
    }

    .empty-state .icon {
        font-size: 2.5rem;
        color: var(--gray-300);
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 300;
    }

    .empty-state h6 {
        font-weight: 600;
        color: var(--gray-700);
        margin-top: 0.5rem;
    }

    .empty-state p {
        font-size: 0.9rem;
        margin: 0;
    }

    @media (max-width: 768px) {
        .page-header .avatar-pet {
            width: 44px;
            height: 44px;
            font-size: 1rem;
        }
        .page-header h2 {
            font-size: 1.3rem;
        }
        .card-modern .card-body {
            padding: 1.25rem;
        }
        .detalle-historia .card-body {
            padding: 1rem;
        }
    }
</style>

<div class="container-fluid py-3">

    {{-- Encabezado --}}
    <div class="page-header mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-pet">
                    CL
                </div>
                <div>
                    <h2>Clientes</h2>
                    <p>Administra los clientes registrados en el sistema.</p>
                </div>
            </div>
            <button type="button" class="btn btn-primary" onclick="mostrarFormularioCliente()">
                + Nuevo cliente
            </button>
        </div>
    </div>

    {{-- Tarjeta principal --}}
    <div class="card card-modern">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="input-group input-group-modern">
                        <span class="input-group-text">Buscar</span>
                        <input type="text" id="buscarCliente" class="form-control" placeholder="Nombre o correo...">
                    </div>
                </div>
            </div>

            <div id="listaClientes">
                <div class="text-center py-5 text-secondary-light">
                    <div class="spinner-border text-primary" role="status" style="width:2.5rem;height:2.5rem;"></div>
                    <p class="mt-3 mb-0">Cargando clientes...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    let clientes = [];

    $(document).ready(function () {
        cargarClientes();
        $('#buscarCliente').on('input', function () {
            const texto = $(this).val().toLowerCase().trim();
            const filtrados = clientes.filter(cliente => {
                return (cliente.name || '').toLowerCase().includes(texto) ||
                       (cliente.email || '').toLowerCase().includes(texto);
            });
            mostrarClientes(filtrados);
        });
    });

    function cargarClientes() {
        $.ajax({
            url: "{{ route('admin.clientes.data') }}",
            type: "GET",
            success: function (response) {
                if (!response.success) {
                    $('#listaClientes').html(`
                        <div class="alert alert-danger">No fue posible cargar los clientes.</div>
                    `);
                    return;
                }
                clientes = response.clientes || [];
                mostrarClientes(clientes);
            },
            error: function (xhr) {
                console.error(xhr);
                $('#listaClientes').html(`
                    <div class="alert alert-danger">No fue posible cargar los clientes.</div>
                `);
            }
        });
    }

    function mostrarClientes(lista) {
        if (lista.length === 0) {
            $('#listaClientes').html(`
                <div class="text-center py-5 text-muted">
                    <span style="font-size:3.5rem;color:var(--gray-300);display:block;">--</span>
                    <h5 class="mt-3 fw-bold" style="color:var(--gray-700);">No se encontraron clientes</h5>
                    <p class="mb-0">No existen clientes que coincidan con la búsqueda.</p>
                </div>
            `);
            return;
        }

        let html = `
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Correo</th>
                            <th>Mascotas</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        lista.forEach(cliente => {
            const mascotas = cliente.mascotas || [];
            html += `
                <tr>
                    <td>
                        <div class="fw-bold" style="color:var(--gray-900);">${cliente.name || 'Sin nombre'}</div>
                    </td>
                    <td>${cliente.email || 'Sin correo'}</td>
                    <td>
                        <span class="badge badge-modern badge-primary-modern">${mascotas.length}</span>
                    </td>
                    <td class="text-end">
                        <button type="button" class="btn btn-outline-modern" onclick="verCliente(${cliente.id})">
                            Ver detalle
                        </button>
                    </td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>
        `;

        $('#listaClientes').html(html);
    }

    function verCliente(clienteId) {
        const cliente = clientes.find(cliente => cliente.id === clienteId);
        if (!cliente) return;

        const mascotas = cliente.mascotas || [];

        let html = `
            <div class="card-modern">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                        <div>
                            <h4 class="fw-bold mb-1" style="color:var(--gray-900);">${cliente.name || 'Sin nombre'}</h4>
                            <div class="text-muted">${cliente.email || 'Sin correo'}</div>
                        </div>
                        <button type="button" class="btn btn-outline-modern" onclick="mostrarListaClientes()">
                            ← Volver
                        </button>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100" style="background:var(--gray-50);">
                                <h5 class="fw-bold mb-3" style="color:var(--gray-900);">Mascotas</h5>
                                <div class="text-muted small mb-3">
                                    ${mascotas.length} mascota${mascotas.length !== 1 ? 's' : ''} registrada${mascotas.length !== 1 ? 's' : ''}
                                </div>
                                <div id="listaMascotasCliente">
        `;

        if (mascotas.length === 0) {
            html += `
                <div class="empty-state">
                    <span class="icon">-</span>
                    <h6>Sin mascotas</h6>
                    <p>Este cliente no tiene mascotas registradas.</p>
                </div>
            `;
        } else {
            mascotas.forEach(mascota => {
                html += `
                    <button type="button"
                        class="w-100 text-start mascota-selector mb-2"
                        onclick="cargarHistoria(${mascota.id}, this)"
                        id="mascotaSelector_${mascota.id}">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-pet">
                                M
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1" style="color:var(--gray-900);">${mascota.nombre || 'Sin nombre'}</h6>
                                <small class="text-muted">
                                    ${mascota.especie || 'Sin especie'}
                                    ${mascota.raza ? ' · ' + mascota.raza : ''}
                                </small>
                            </div>
                            <span class="chevron">›</span>
                        </div>
                    </button>
                `;
            });
        }

        html += `
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div id="detalleHistoriaCliente" class="detalle-historia p-3">
                                <div class="empty-state">
                                    <span class="icon" style="font-size:3rem;">-</span>
                                    <h5 class="mt-3">Seleccione una mascota</h5>
                                    <p>Seleccione una mascota de la lista para consultar su historia clínica.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#listaClientes').html(html);
    }

    function cargarHistoria(mascotaId, boton) {
        $('.mascota-selector').removeClass('active');
        $(boton).addClass('active');

        const contenedor = $('#detalleHistoriaCliente');

        contenedor.html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" style="width:2.5rem;height:2.5rem;"></div>
                <p class="mt-3 text-muted mb-0">Cargando historia clínica...</p>
            </div>
        `);

        $.ajax({
            url: "{{ url('/veterinario/historia-clinica') }}/" + mascotaId,
            type: "GET",
            success: function (response) {
                if (!response.success) {
                    contenedor.html(`
                        <div class="alert alert-danger">No fue posible cargar la historia clínica.</div>
                    `);
                    return;
                }
                mostrarHistoriaMascota(response.mascota, contenedor);
            },
            error: function (xhr) {
                console.error(xhr);
                contenedor.html(`
                    <div class="alert alert-danger">No fue posible cargar la historia clínica.</div>
                `);
            }
        });
    }

    function mostrarHistoriaMascota(mascota, contenedor) {
        const consultas = mascota.consultas || [];
        const vacunas = mascota.vacunas || [];
        const desparasitaciones = mascota.desparasitaciones || [];
        const tratamientos = mascota.tratamientos || [];

        let html = `
            <div>
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-1" style="color:var(--gray-900);">${mascota.nombre || 'Sin nombre'}</h5>
                        <div class="text-muted small">${mascota.especie || ''} ${mascota.raza ? '· ' + mascota.raza : ''}</div>
                    </div>
                    <span class="badge badge-modern badge-primary-modern">${consultas.length} consultas</span>
                </div>

                <div class="historia-seccion">
                    <div class="seccion-header">
                        <h6>Información de la mascota</h6>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4"><small class="text-muted d-block">Nombre</small><strong>${mascota.nombre || 'No registrado'}</strong></div>
                        <div class="col-md-4"><small class="text-muted d-block">Especie</small><span>${mascota.especie || 'No registrado'}</span></div>
                        <div class="col-md-4"><small class="text-muted d-block">Raza</small><span>${mascota.raza || 'No registrada'}</span></div>
                        <div class="col-md-4"><small class="text-muted d-block">Sexo</small><span>${mascota.sexo || 'No registrado'}</span></div>
                        <div class="col-md-4"><small class="text-muted d-block">Fecha nacimiento</small><span>${formatearFecha(mascota.fecha_nacimiento)}</span></div>
                        <div class="col-md-4"><small class="text-muted d-block">Peso</small><span>${mascota.peso ? mascota.peso + ' kg' : 'No registrado'}</span></div>
                        <div class="col-md-4"><small class="text-muted d-block">Color</small><span>${mascota.color || 'No registrado'}</span></div>
                        <div class="col-md-8"><small class="text-muted d-block">Observaciones</small><span>${mascota.observaciones || 'Sin observaciones'}</span></div>
                    </div>
                </div>

                <div class="historia-seccion">
                    <div class="seccion-header">
                        <h6>Consultas</h6>
                        <span class="badge badge-modern badge-primary-modern">${consultas.length}</span>
                    </div>
        `;

        if (consultas.length === 0) {
            html += `<div class="empty-state"><span class="icon">-</span><p>No existen consultas registradas.</p></div>`;
        } else {
            consultas.forEach((consulta, index) => {
                html += `
                    <div class="historia-item">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                            <span class="badge bg-secondary">Consulta ${index + 1}</span>
                            <small class="text-muted">${formatearFecha(consulta.fecha)} · ${consulta.veterinario?.name || 'Veterinario'}</small>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6"><small class="text-muted d-block">Motivo</small><span>${consulta.motivo || 'No registrado'}</span></div>
                            <div class="col-md-6"><small class="text-muted d-block">Diagnóstico</small><span>${consulta.diagnostico || 'No registrado'}</span></div>
                            <div class="col-12"><small class="text-muted d-block">Observaciones</small><span>${consulta.observaciones || 'Sin observaciones'}</span></div>
                        </div>
                    </div>
                `;
            });
        }

        html += `
                </div>

                <div class="historia-seccion">
                    <div class="seccion-header">
                        <h6>Vacunas</h6>
                        <span class="badge badge-modern badge-success-modern">${vacunas.length}</span>
                    </div>
        `;

        if (vacunas.length === 0) {
            html += `<div class="empty-state"><span class="icon">-</span><p>No existen vacunas registradas.</p></div>`;
        } else {
            vacunas.forEach((vacuna, index) => {
                html += `
                    <div class="historia-item">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                            <span class="badge bg-success">Vacuna ${index + 1}</span>
                            <small class="text-muted">${formatearFecha(vacuna.fecha_aplicacion)}</small>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6"><small class="text-muted d-block">Nombre</small><span>${vacuna.nombre || 'No registrado'}</span></div>
                            <div class="col-md-6"><small class="text-muted d-block">Veterinario</small><span>${vacuna.veterinario?.name || 'No registrado'}</span></div>
                            <div class="col-md-6"><small class="text-muted d-block">Próxima dosis</small><span>${formatearFecha(vacuna.proxima_dosis)}</span></div>
                            <div class="col-md-6"><small class="text-muted d-block">Observaciones</small><span>${vacuna.observaciones || 'Sin observaciones'}</span></div>
                        </div>
                    </div>
                `;
            });
        }

        html += `
                </div>

                <div class="historia-seccion">
                    <div class="seccion-header">
                        <h6>Desparasitaciones</h6>
                        <span class="badge badge-modern badge-warning-modern">${desparasitaciones.length}</span>
                    </div>
        `;

        if (desparasitaciones.length === 0) {
            html += `<div class="empty-state"><span class="icon">-</span><p>No existen desparasitaciones registradas.</p></div>`;
        } else {
            desparasitaciones.forEach((d, index) => {
                html += `
                    <div class="historia-item">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                            <span class="badge bg-warning text-dark">Desparasitación ${index + 1}</span>
                            <small class="text-muted">${formatearFecha(d.fecha)}</small>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6"><small class="text-muted d-block">Producto</small><span>${d.producto || 'No registrado'}</span></div>
                            <div class="col-md-6"><small class="text-muted d-block">Veterinario</small><span>${d.veterinario?.name || 'No registrado'}</span></div>
                            <div class="col-md-6"><small class="text-muted d-block">Próxima fecha</small><span>${formatearFecha(d.proxima_fecha)}</span></div>
                            <div class="col-md-6"><small class="text-muted d-block">Observaciones</small><span>${d.observaciones || 'Sin observaciones'}</span></div>
                        </div>
                    </div>
                `;
            });
        }

        html += `
                </div>

                <div class="historia-seccion">
                    <div class="seccion-header">
                        <h6>Tratamientos</h6>
                        <span class="badge badge-modern badge-danger-modern">${tratamientos.length}</span>
                    </div>
        `;

        if (tratamientos.length === 0) {
            html += `<div class="empty-state"><span class="icon">-</span><p>No existen tratamientos registrados.</p></div>`;
        } else {
            tratamientos.forEach((t, index) => {
                html += `
                    <div class="historia-item">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                            <span class="badge bg-danger">Tratamiento ${index + 1}</span>
                            <small class="text-muted">${formatearFecha(t.fecha_inicio)}</small>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-6"><small class="text-muted d-block">Nombre</small><span>${t.nombre || 'No registrado'}</span></div>
                            <div class="col-md-6"><small class="text-muted d-block">Veterinario</small><span>${t.veterinario?.name || 'No registrado'}</span></div>
                            <div class="col-md-6"><small class="text-muted d-block">Finalización</small><span>${formatearFecha(t.fecha_fin)}</span></div>
                            <div class="col-md-6"><small class="text-muted d-block">Estado</small><span class="badge bg-secondary">${t.estado || 'Sin estado'}</span></div>
                            <div class="col-12"><small class="text-muted d-block">Observaciones</small><span>${t.observaciones || 'Sin observaciones'}</span></div>
                        </div>
                    </div>
                `;
            });
        }

        html += `
                </div>
            </div>
        `;

        contenedor.html(html);
    }

    function formatearFecha(fecha) {
        if (!fecha) return 'No registrada';
        const fechaObj = new Date(fecha);
        if (isNaN(fechaObj.getTime())) return 'No válida';
        return fechaObj.toLocaleDateString('es-BO', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    function mostrarListaClientes() {
        mostrarClientes(clientes);
    }

    function mostrarFormularioCliente()
    {
        $('#listaClientes').html(`
            <div class="card-modern">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="fw-bold mb-1" style="color:var(--gray-900);">Nuevo usuario</h4>
                            <div class="text-muted">Registra un nuevo usuario en el sistema.</div>
                        </div>
                        <button type="button" class="btn btn-outline-modern" onclick="mostrarListaClientes()">
                            ← Volver
                        </button>
                    </div>
                    <form id="formNuevoCliente">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre completo</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Correo electrónico</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Contraseña</label>
                                <input type="password" name="password" class="form-control" minlength="6" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Rol</label>
                                <select name="role" class="form-select" required>
                                    <option value="">Seleccione un rol</option>
                                    <option value="cliente">Cliente</option>
                                    <option value="veterinario">Veterinario</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div id="errorNuevoCliente"></div>
                            </div>
                            <div class="col-12 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-outline-modern" onclick="mostrarListaClientes()">
                                    Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    Guardar usuario
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        `);

        $('#formNuevoCliente').on('submit',function(e){
            e.preventDefault();
            guardarCliente();
        });
    }

    function guardarCliente()
    {
        const formulario=$('#formNuevoCliente');
        const boton=formulario.find('button[type="submit"]');
        const datos=new FormData(formulario[0]);

        boton.prop('disabled',true);
        boton.html(`
            <span class="spinner-border spinner-border-sm"></span>
            Guardando...
        `);

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        
        $.ajax({
            url:"{{ route('admin.usuarios.guardar') }}",
            type:"POST",
            data:datos,
            processData:false,
            contentType:false,
            headers:{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
            success:function(response){
                if(!response.success){
                    mostrarErrorCliente('No fue posible registrar el usuario.');
                    boton.prop('disabled',false);
                    boton.text('Guardar usuario');
                    return;
                }

                toastr.success(response.message||'Usuario registrado correctamente.');
                cargarClientes();
            },
            error:function(xhr){
                if(xhr.status===422){
                    const errores=xhr.responseJSON?.errors||{};
                    let mensaje='';

                    Object.values(errores).forEach(error=>{
                        mensaje+=`${error[0]}<br>`;
                    });

                    mostrarErrorCliente(mensaje);
                }else{
                    mostrarErrorCliente('No fue posible registrar el usuario.');
                }

                boton.prop('disabled',false);
                boton.text('Guardar usuario');
            }
        });
    }

    function mostrarErrorCliente(mensaje)
    {
        $('#errorNuevoCliente').html(`
            <div class="alert alert-danger mb-0">
                ${mensaje}
            </div>
        `);
    }
</script>
@endsection