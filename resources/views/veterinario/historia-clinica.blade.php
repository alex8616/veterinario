@extends('layouts.my-dashboard-layout')

@section('content')
<link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    :root {
        --primary: #6C63FF;
        --primary-light: #8B83FF;
        --primary-dark: #5A52D5;
        --secondary: #F8F9FE;
        --gray-light: #F1F4F9;
        --gray-medium: #E2E8F0;
        --text-dark: #1E293B;
        --text-muted: #64748B;
        --shadow-sm: 0 2px 8px rgba(108, 99, 255, 0.08);
        --shadow-md: 0 8px 30px rgba(108, 99, 255, 0.12);
        --shadow-lg: 0 15px 50px rgba(108, 99, 255, 0.15);
        --radius: 16px;
        --radius-sm: 10px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body {
        background: #F8FAFC;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .avatar-pet {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #6C63FF, #8B83FF);
        color: white;
        font-size: 1.8rem;
        box-shadow: var(--shadow-sm);
    }

    .page-title {
        color: var(--text-dark);
        font-weight: 700;
        letter-spacing: -0.02em;
    }

    .page-subtitle {
        color: var(--text-muted);
        font-weight: 400;
    }

    .card-modern {
        background: white;
        border: none;
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    .card-modern:hover {
        box-shadow: var(--shadow-md);
    }

    .input-group-modern .input-group-text {
        background: var(--gray-light);
        border: 2px solid var(--gray-medium);
        border-right: none;
        color: var(--text-muted);
        border-radius: var(--radius-sm) 0 0 var(--radius-sm);
        padding: 0.6rem 1rem;
    }

    .input-group-modern .form-control {
        border: 2px solid var(--gray-medium);
        border-left: none;
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: var(--transition);
    }

    .input-group-modern .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.15);
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border: none;
        border-radius: var(--radius-sm);
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        color: white;
        transition: var(--transition);
        box-shadow: 0 4px 12px rgba(108, 99, 255, 0.3);
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(108, 99, 255, 0.4);
        color: white;
    }

    .btn-outline-modern {
        border: 2px solid var(--gray-medium);
        border-radius: var(--radius-sm);
        padding: 0.4rem 1rem;
        font-weight: 500;
        color: var(--text-dark);
        transition: var(--transition);
        background: transparent;
    }

    .btn-outline-modern:hover {
        border-color: var(--primary);
        background: var(--secondary);
        color: var(--primary);
    }

    .cliente-card-modern {
        border: 2px solid var(--gray-light);
        border-radius: var(--radius);
        padding: 1.5rem;
        background: white;
        transition: var(--transition);
        cursor: default;
    }

    .cliente-card-modern:hover {
        border-color: var(--primary-light);
        box-shadow: var(--shadow-md);
        transform: translateY(-4px);
    }

    .mascota-card-modern {
        border: 2px solid var(--gray-light);
        border-radius: var(--radius-sm);
        padding: 1rem 1.2rem;
        background: white;
        transition: var(--transition);
        cursor: pointer;
    }

    .mascota-card-modern:hover {
        border-color: var(--primary);
        background: var(--secondary);
        box-shadow: var(--shadow-sm);
    }

    .mascota-icon-modern {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #EEF2FF, #E0E7FF);
        color: var(--primary);
        font-size: 1.4rem;
        transition: var(--transition);
    }

    .mascota-card-modern:hover .mascota-icon-modern {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        color: white;
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
    }

    .badge-success-modern {
        background: #10B981;
        color: white;
    }

    .badge-warning-modern {
        background: #F59E0B;
        color: #1E293B;
    }

    .badge-danger-modern {
        background: #EF4444;
        color: white;
    }

    .badge-info-modern {
        background: #3B82F6;
        color: white;
    }

    .section-title {
        font-weight: 700;
        color: var(--text-dark);
        letter-spacing: -0.01em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .registro-item {
        border: 2px solid var(--gray-light);
        border-radius: var(--radius-sm);
        padding: 1.2rem;
        background: white;
        transition: var(--transition);
        margin-bottom: 1rem;
    }

    .registro-item:hover {
        border-color: var(--primary-light);
        box-shadow: var(--shadow-sm);
    }

    .registro-fecha {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 500;
        background: var(--gray-light);
        display: inline-block;
        padding: 0.2rem 0.8rem;
        border-radius: 50px;
    }

    .sin-registros-modern {
        text-align: center;
        padding: 2.5rem 1rem;
        color: var(--text-muted);
    }

    .sin-registros-modern i {
        font-size: 3rem;
        color: var(--gray-medium);
        margin-bottom: 0.5rem;
        display: block;
    }

    .sin-registros-modern p {
        font-size: 0.95rem;
        margin: 0;
    }

    .tabla-modern {
        border-collapse: separate;
        border-spacing: 0 0.4rem;
    }

    .tabla-modern thead th {
        background: var(--gray-light);
        color: var(--text-dark);
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        padding: 0.8rem 1rem;
        border: none;
    }

    .tabla-modern tbody td {
        background: white;
        padding: 0.8rem 1rem;
        border: none;
        border-radius: 0;
        vertical-align: middle;
    }

    .tabla-modern tbody tr {
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        transition: var(--transition);
    }

    .tabla-modern tbody tr:hover td {
        background: var(--secondary);
    }

    .tabla-modern tbody td:first-child {
        border-radius: var(--radius-sm) 0 0 var(--radius-sm);
    }

    .tabla-modern tbody td:last-child {
        border-radius: 0 var(--radius-sm) var(--radius-sm) 0;
    }

    .badge-estado {
        padding: 0.25rem 0.8rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .badge-estado.activo {
        background: #D1FAE5;
        color: #065F46;
    }

    .badge-estado.finalizado {
        background: #FEE2E2;
        color: #991B1B;
    }

    .badge-estado.pendiente {
        background: #FEF3C7;
        color: #92400E;
    }

    .badge-estado.cancelado {
        background: #E5E7EB;
        color: #4B5563;
    }

    .stats-card {
        background: white;
        border-radius: var(--radius-sm);
        padding: 1rem 1.2rem;
        border: 2px solid var(--gray-light);
        transition: var(--transition);
    }

    .stats-card:hover {
        border-color: var(--primary-light);
    }

    .stats-number {
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1;
    }

    .stats-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-top: 0.25rem;
    }

    @media (max-width: 768px) {
        .avatar-pet {
            width: 44px;
            height: 44px;
            font-size: 1.2rem;
        }
        .page-title {
            font-size: 1.4rem;
        }
        .cliente-card-modern {
            padding: 1rem;
        }
        .mascota-card-modern {
            padding: 0.8rem;
        }
    }
</style>

<div class="container-fluid py-4">
    {{-- Encabezado --}}
    <div class="p-4 mb-5 rounded-4" style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); box-shadow: 0 4px 20px rgba(74,108,247,0.10);">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div>
                <h2 class="page-title mb-0">Historia Clínica</h2>
                <p class="page-subtitle mb-0">Busca un cliente para consultar las historias clínicas de sus mascotas.</p>
            </div>
        </div>
    </div>

    {{-- Buscar cliente --}}
    <div class="card-modern mb-4">
        <div class="card-body p-4">
            <h5 class="section-title mb-3"><i class="lni lni-search-1"></i> Buscar cliente</h5>
            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <div class="input-group input-group-modern">
                        <span class="input-group-text"><i class="lni lni-search-1"></i></span>
                        <input type="text" id="buscarCliente" class="form-control" placeholder="Nombre o correo del cliente...">
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-primary-modern w-100" onclick="buscarClientes()">
                        <i class="lni lni-search-1"></i> Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Resultados clientes --}}
    <div id="resultadosClientes">
        <div class="card-modern">
            <div class="card-body text-center py-5 text-muted">
                <i class="lni lni-users" style="font-size:3.5rem; color: var(--gray-medium);"></i>
                <h5 class="mt-3 fw-bold" style="color: var(--text-dark);">Buscar un cliente</h5>
                <p class="mb-0">Escribe el nombre o correo del cliente para comenzar.</p>
            </div>
        </div>
    </div>

    {{-- Historia clínica --}}
    <div id="historiaClinica" class="mt-4" style="display:none;"></div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    let clientes = [];

    $(document).ready(function () {
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 4000,
            preventDuplicates: true,
        };
    });

    function buscarClientes() {
        const buscar = $('#buscarCliente').val().trim();
        if (!buscar) {
            toastr.warning('Escribe el nombre o correo del cliente.');
            return;
        }
        $('#resultadosClientes').html(`
            <div class="card-modern">
                <div class="card-body text-center py-5">
                    <div class="spinner-border text-primary" style="width:3rem;height:3rem;border-width:0.25rem;"></div>
                    <p class="mt-3 mb-0 text-muted">Buscando clientes...</p>
                </div>
            </div>
        `);
        $.ajax({
            url: "{{ route('veterinario.historia.clientes') }}",
            type: "GET",
            data: { buscar: buscar },
            success: function (response) {
                if (!response.success) {
                    toastr.error('No fue posible realizar la búsqueda.');
                    return;
                }
                clientes = response.clientes || [];
                mostrarClientes();
            },
            error: function (xhr) {
                console.error(xhr);
                toastr.error('No fue posible buscar los clientes.');
            }
        });
    }

    function mostrarClientes() {
        if (clientes.length === 0) {
            $('#resultadosClientes').html(`
                <div class="card-modern">
                    <div class="card-body text-center py-5 text-muted">
                        <i class="lni lni-user" style="font-size:3.5rem; color: var(--gray-medium);"></i>
                        <h5 class="mt-3 fw-bold" style="color: var(--text-dark);">No se encontraron clientes</h5>
                        <p class="mb-0">No existe ningún cliente con ese nombre o correo.</p>
                    </div>
                </div>
            `);
            return;
        }
        let html = `
            <div class="card-modern">
                <div class="card-body p-4">
                    <h5 class="section-title mb-4"><i class="lni lni-user-multiple-4"></i> Clientes encontrados</h5>
                    <div class="row g-4">
        `;
        clientes.forEach(cliente => {
            const mascotas = cliente.mascotas || [];
            html += `
                <div class="col-md-6">
                    <div class="cliente-card-modern">
                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                            <div>
                                <h6 class="fw-bold mb-1" style="color:var(--text-dark);">${cliente.name}</h6>
                                <small class="text-muted">${cliente.email}</small>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-outline-modern btn-sm" onclick="verHistorialCliente(${cliente.id})">
                                    <i class="lni lni-book"></i> Historial
                                </button>
                                <button type="button" class="btn btn-outline-modern btn-sm" onclick="exportarHistorialCliente(${cliente.id})">
                                    <i class="lni lni-printer"></i> PDF
                                </button>
                            </div>
                        </div>
                        <hr class="my-3" style="border-color: var(--gray-light);">
                        <div>
                            <small class="text-muted d-block mb-2 fw-semibold">Mascotas (${mascotas.length})</small>
            `;
            if (mascotas.length === 0) {
                html += `<div class="text-muted small"><i class="lni lni-sad"></i> Este cliente no tiene mascotas registradas.</div>`;
            } else {
                mascotas.forEach(mascota => {
                    html += `
                        <div class="mascota-card-modern mb-2">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-center gap-3" style="cursor:pointer; flex:1;" onclick="cargarHistoria(${mascota.id})">
                                    <div class="mascota-icon-modern"><i class="lni lni-paw"></i></div>
                                    <div>
                                        <div class="fw-bold" style="color:var(--text-dark);">${mascota.nombre}</div>
                                        <small class="text-muted">${mascota.especie || ''}${mascota.raza ? ' · ' + mascota.raza : ''}</small>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-modern btn-sm" onclick="event.stopPropagation(); exportarHistoriaPDF(${mascota.id})" title="Exportar historia clínica">
                                    <i class="lni lni-printer"></i> PDF
                                </button>
                            </div>
                        </div>
                    `;
                });
            }
            html += `
                        </div>
                    </div>
                </div>
            `;
        });
        html += `
                    </div>
                </div>
            </div>
        `;
        $('#resultadosClientes').html(html);
    }

    function cargarHistoria(mascotaId) {
        $('#historiaClinica').show().html(`
            <div class="card-modern">
                <div class="card-body text-center py-5">
                    <div class="spinner-border text-primary" style="width:3rem;height:3rem;border-width:0.25rem;"></div>
                    <p class="mt-3 mb-0 text-muted">Cargando historia clínica...</p>
                </div>
            </div>
        `);
        $.ajax({
            url: "{{ url('/veterinario/historia-clinica') }}/" + mascotaId,
            type: "GET",
            success: function (response) {
                if (!response.success) {
                    toastr.error('No fue posible cargar la historia clínica.');
                    return;
                }
                mostrarHistoriaClinica(response.mascota);
            },
            error: function (xhr) {
                console.error(xhr);
                toastr.error('No fue posible cargar la historia clínica.');
            }
        });
    }

    function mostrarHistoriaClinica(mascota) {
        console.log('Historia clínica:', mascota);
        const consultas = mascota.consultas || [];
        const vacunas = mascota.vacunas || [];
        const desparasitaciones = mascota.desparasitaciones || [];
        const tratamientos = mascota.tratamientos || [];

        let html = `
            {{-- Información de mascota --}}
            <div class="card-modern mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                        <div>
                            <h4 class="fw-bold mb-1" style="color:var(--text-dark);">${mascota.nombre}</h4>
                            <div class="text-muted">${mascota.especie || ''}${mascota.raza ? ' · ' + mascota.raza : ''}</div>
                        </div>
                        <span class="badge badge-modern badge-primary-modern">Historia clínica</span>
                    </div>
                    <hr class="my-3" style="border-color: var(--gray-light);">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <small class="text-muted d-block fw-semibold">Sexo</small>
                            <strong>${mascota.sexo || 'No registrado'}</strong>
                        </div>
                        <div class="col-md-3 col-6">
                            <small class="text-muted d-block fw-semibold">Peso</small>
                            <strong>${mascota.peso ? mascota.peso + ' kg' : 'No registrado'}</strong>
                        </div>
                        <div class="col-md-3 col-6">
                            <small class="text-muted d-block fw-semibold">Color</small>
                            <strong>${mascota.color || 'No registrado'}</strong>
                        </div>
                        <div class="col-md-3 col-6">
                            <small class="text-muted d-block fw-semibold">Fecha nacimiento</small>
                            <strong>${mascota.fecha_nacimiento ? mascota.fecha_nacimiento.substring(0,10) : 'No registrada'}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Consultas --}}
            <div class="card-modern mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                        <h5 class="section-title"><i class="lni lni-stethoscope" style="color:var(--primary);"></i> Consultas</h5>
                        <span class="badge badge-modern badge-primary-modern">${consultas.length}</span>
                    </div>
        `;
        if (consultas.length === 0) {
            html += `
                <div class="sin-registros-modern">
                    <i class="lni lni-notepad"></i>
                    <p>No existen consultas registradas.</p>
                </div>
            `;
        } else {
            consultas.forEach(consulta => {
                const fecha = consulta.fecha ? consulta.fecha.substring(0,10) : 'Sin fecha';
                const veterinario = consulta.veterinario ? consulta.veterinario.name : 'Veterinario no registrado';
                html += `
                    <div class="registro-item">
                        <div class="d-flex flex-wrap justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold mb-1" style="color:var(--text-dark);">${consulta.motivo || 'Consulta veterinaria'}</h6>
                                <span class="registro-fecha"><i class="lni lni-calendar"></i> ${fecha}</span>
                                <span class="registro-fecha ms-1"><i class="lni lni-user"></i> ${veterinario}</span>
                            </div>
                            <span class="badge badge-modern bg-light text-dark">Consulta</span>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <small class="text-muted d-block fw-semibold">Diagnóstico</small>
                                <div>${consulta.diagnostico || 'No registrado'}</div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block fw-semibold">Peso</small>
                                <div>${consulta.peso ? consulta.peso + ' kg' : 'No registrado'}</div>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted d-block fw-semibold">Temperatura</small>
                                <div>${consulta.temperatura ? consulta.temperatura + ' °C' : 'No registrada'}</div>
                            </div>
                            <div class="col-12">
                                <small class="text-muted d-block fw-semibold">Observaciones</small>
                                <div>${consulta.observaciones || 'Sin observaciones'}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        html += `
                </div>
            </div>

            {{-- Vacunas --}}
            <div class="card-modern mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                        <h5 class="section-title">Vacunas</h5>
                        <span class="badge badge-modern badge-success-modern">${vacunas.length}</span>
                    </div>
        `;
        if (vacunas.length === 0) {
            html += `
                <div class="sin-registros-modern">
                    <p>No existen vacunas registradas.</p>
                </div>
            `;
        } else {
            html += `<div class="table-responsive"><table class="table tabla-modern"><thead><tr><th>Vacuna</th><th>Aplicación</th><th>Próxima dosis</th><th>Veterinario</th><th>Observaciones</th></tr></thead><tbody>`;
            vacunas.forEach(vacuna => {
                html += `
                    <tr>
                        <td class="fw-semibold" style="color:var(--text-dark);">${vacuna.nombre || 'Sin nombre'}</td>
                        <td>${vacuna.fecha_aplicacion ? vacuna.fecha_aplicacion.substring(0,10) : 'No registrada'}</td>
                        <td>${vacuna.proxima_dosis ? vacuna.proxima_dosis.substring(0,10) : 'No programada'}</td>
                        <td>${vacuna.veterinario ? vacuna.veterinario.name : 'No registrado'}</td>
                        <td>${vacuna.observaciones || 'Sin observaciones'}</td>
                    </tr>
                `;
            });
            html += `</tbody></table></div>`;
        }
        html += `
                </div>
            </div>

            {{-- Desparasitaciones --}}
            <div class="card-modern mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                        <h5 class="section-title">Desparasitaciones</h5>
                        <span class="badge badge-modern badge-warning-modern">${desparasitaciones.length}</span>
                    </div>
        `;
        if (desparasitaciones.length === 0) {
            html += `
                <div class="sin-registros-modern">
                    <i class="lni lni-reload"></i>
                    <p>No existen desparasitaciones registradas.</p>
                </div>
            `;
        } else {
            html += `<div class="table-responsive"><table class="table tabla-modern"><thead><tr><th>Producto</th><th>Fecha</th><th>Próxima fecha</th><th>Veterinario</th><th>Observaciones</th></tr></thead><tbody>`;
            desparasitaciones.forEach(d => {
                html += `
                    <tr>
                        <td class="fw-semibold" style="color:var(--text-dark);">${d.producto || 'Sin producto'}</td>
                        <td>${d.fecha ? d.fecha.substring(0,10) : 'No registrada'}</td>
                        <td>${d.proxima_fecha ? d.proxima_fecha.substring(0,10) : 'No programada'}</td>
                        <td>${d.veterinario ? d.veterinario.name : 'No registrado'}</td>
                        <td>${d.observaciones || 'Sin observaciones'}</td>
                    </tr>
                `;
            });
            html += `</tbody></table></div>`;
        }
        html += `
                </div>
            </div>

            {{-- Tratamientos --}}
            <div class="card-modern mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                        <h5 class="section-title">Tratamientos</h5>
                        <span class="badge badge-modern badge-danger-modern">${tratamientos.length}</span>
                    </div>
        `;
        if (tratamientos.length === 0) {
            html += `
                <div class="sin-registros-modern">
                    <i class="lni lni-medical"></i>
                    <p>No existen tratamientos registrados.</p>
                </div>
            `;
        } else {
            tratamientos.forEach(tratamiento => {
                const estadoClass = {
                    'activo': 'activo',
                    'finalizado': 'finalizado',
                    'pendiente': 'pendiente',
                    'cancelado': 'cancelado'
                }[(tratamiento.estado || '').toLowerCase()] || '';
                html += `
                    <div class="registro-item">
                        <div class="d-flex flex-wrap justify-content-between align-items-start">
                            <div>
                                <h6 class="fw-bold mb-1" style="color:var(--text-dark);">${tratamiento.nombre || 'Tratamiento'}</h6>
                                <span class="registro-fecha"><i class="lni lni-calendar"></i> ${tratamiento.fecha_inicio ? tratamiento.fecha_inicio.substring(0,10) : 'Sin fecha'}${tratamiento.fecha_fin ? ' → ' + tratamiento.fecha_fin.substring(0,10) : ''}</span>
                            </div>
                            <span class="badge badge-estado ${estadoClass}">${tratamiento.estado || 'Sin estado'}</span>
                        </div>
                        <hr class="my-2" style="border-color: var(--gray-light);">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block fw-semibold">Descripción</small>
                                <div>${tratamiento.descripcion || 'Sin descripción'}</div>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block fw-semibold">Veterinario</small>
                                <div>${tratamiento.veterinario ? tratamiento.veterinario.name : 'No registrado'}</div>
                            </div>
                            <div class="col-12">
                                <small class="text-muted d-block fw-semibold">Observaciones</small>
                                <div>${tratamiento.observaciones || 'Sin observaciones'}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        html += `
                </div>
            </div>
        `;
        $('#historiaClinica').html(html);
        $('html, body').animate({ scrollTop: $('#historiaClinica').offset().top - 20 }, 400);
    }

    function exportarHistoriaPDF(mascotaId) {
        window.open(`/veterinario/historia-clinica/${mascotaId}/pdf`, '_blank');
    }

    function verHistorialCliente(clienteId) {
        $.ajax({
            url: `/veterinario/historia-clinica/cliente/${clienteId}`,
            type: 'GET',
            beforeSend: function () {
                $('#resultadosClientes').html(`
                    <div class="card-modern">
                        <div class="card-body text-center py-5">
                            <div class="spinner-border text-primary" style="width:3rem;height:3rem;border-width:0.25rem;"></div>
                            <h5 class="mt-3 fw-bold" style="color:var(--text-dark);">Cargando historial clínico...</h5>
                        </div>
                    </div>
                `);
            },
            success: function (response) {
                if (!response.success) {
                    toastr.error('No fue posible cargar el historial.');
                    return;
                }
                mostrarHistorialCliente(response.cliente);
            },
            error: function (xhr) {
                console.error(xhr);
                toastr.error('No fue posible cargar el historial clínico.');
            }
        });
    }

    function mostrarHistorialCliente(cliente) {
        const mascotas = cliente.mascotas || [];
        let html = `
            <div class="card-modern">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-3">
                        <div>
                            <h4 class="fw-bold mb-1" style="color:var(--text-dark);"><i class="lni lni-book" style="color:var(--primary);"></i> Historial clínico</h4>
                            <h5 class="mb-1" style="color:var(--text-dark);">${cliente.name}</h5>
                            <div class="text-muted">${cliente.email}</div>
                            <div class="mt-2">
                                <span class="badge badge-modern badge-primary-modern">${mascotas.length} ${mascotas.length === 1 ? 'mascota' : 'mascotas'}</span>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-outline-modern" onclick="volverClientes()"><i class="lni lni-arrow-left"></i> Volver</button>
                        </div>
                    </div>
                    <hr style="border-color: var(--gray-light);">
        `;
        if (mascotas.length === 0) {
            html += `
                <div class="sin-registros-modern py-5">
                    <i class="lni lni-paw"></i>
                    <p style="font-size:1rem;">Este cliente no tiene mascotas.</p>
                </div>
            `;
        } else {
            mascotas.forEach(mascota => {
                const consultas = mascota.consultas || [];
                const vacunas = mascota.vacunas || [];
                const desparasitaciones = mascota.desparasitaciones || [];
                const tratamientos = mascota.tratamientos || [];
                html += `
                    <div class="card-modern mb-4" style="border:2px solid var(--gray-light);">
                        <div class="card-body p-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="mascota-icon-modern"><i class="lni lni-paw"></i></div>
                                    <div>
                                        <h5 class="fw-bold mb-1" style="color:var(--text-dark);">${mascota.nombre}</h5>
                                        <small class="text-muted">${mascota.especie || ''}${mascota.raza ? ' · ' + mascota.raza : ''}${mascota.sexo ? ' · ' + mascota.sexo : ''}</small>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary-modern btn-sm" onclick="cargarHistoria(${mascota.id})">Ver historia</button>
                            </div>
                            <div class="row g-3 mt-2">
                                <div class="col-md-3 col-6">
                                    <div class="stats-card">
                                        <div class="stats-number" style="color:var(--primary);">${consultas.length}</div>
                                        <div class="stats-label"><i class="lni lni-stethoscope"></i> Consultas</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="stats-card">
                                        <div class="stats-number" style="color:#10B981;">${vacunas.length}</div>
                                        <div class="stats-label">Vacunas</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="stats-card">
                                        <div class="stats-number" style="color:#F59E0B;">${desparasitaciones.length}</div>
                                        <div class="stats-label">Desparasitaciones</div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6">
                                    <div class="stats-card">
                                        <div class="stats-number" style="color:#EF4444;">${tratamientos.length}</div>
                                        <div class="stats-label">Tratamientos</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        html += `
                </div>
            </div>
        `;
        $('#resultadosClientes').html(html);
    }

    function volverClientes() {
        mostrarClientes();
    }

    function exportarHistorialCliente(clienteId) {
        window.open(`/veterinario/historia-clinica/cliente/${clienteId}/pdf`, '_blank');
    }
</script>
@endsection