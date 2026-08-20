@extends('layouts.my-dashboard-layout')

@section('content')
<link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .card {
        border: none;
        border-radius: 20px;
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(12px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.04);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        border: 1px solid rgba(255,255,255,0.3);
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 48px rgba(0,0,0,0.07);
    }
    .form-select, .form-control {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        padding: 0.7rem 1rem;
        background: #f8fafc;
        transition: 0.2s;
        font-size: 0.95rem;
    }
    .form-select:focus, .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79,70,229,0.12);
        background: #fff;
    }
    .badge-soft-primary {
        background: #eef2ff;
        color: #4f46e5;
        font-weight: 500;
        padding: 0.35rem 1rem;
        border-radius: 50px;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .badge-soft-success {
        background: #dcfce7;
        color: #16a34a;
        font-weight: 500;
        padding: 0.35rem 1rem;
        border-radius: 50px;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .badge-soft-secondary {
        background: #f1f5f9;
        color: #475569;
        font-weight: 500;
        padding: 0.35rem 1rem;
        border-radius: 50px;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .badge-soft-danger {
        background: #fee2e2;
        color: #dc2626;
        font-weight: 500;
        padding: 0.35rem 1rem;
        border-radius: 50px;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    .consulta-item, .vacuna-item, .desparasitacion-item, .tratamiento-item {
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        transition: all 0.2s ease;
        cursor: pointer;
        background: #ffffff;
        margin-bottom: 0.75rem;
        position: relative;
    }
    .consulta-item:hover, .vacuna-item:hover, .desparasitacion-item:hover, .tratamiento-item:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.04);
    }
    .consulta-seleccionada, .vacuna-seleccionada, .desparasitacion-seleccionada, .tratamiento-seleccionado {
        background: #eef2ff !important;
        border-color: #4f46e5 !important;
        box-shadow: 0 4px 20px rgba(79,70,229,0.10);
    }
    .avatar-pet {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.6rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(79,70,229,0.25);
    }
    .detalle-bloque {
        background: #f8fafc;
        border-radius: 14px;
        padding: 1rem 1.25rem;
        border-left: 4px solid #4f46e5;
        margin-bottom: 1rem;
    }
    .detalle-bloque:last-child { margin-bottom: 0; }
    .text-secondary-light { color: #64748b; }
    .spinner-border { color: #4f46e5; }
    .info-placeholder {
        background: #f8fafc;
        border: 1px solid #e9edf5;
        border-radius: 14px;
        min-height: 70px;
        display: flex;
        align-items: center;
        padding: 0.75rem 1.25rem;
        gap: 0.75rem;
        color: #64748b;
        font-size: 0.95rem;
    }
    .info-placeholder i { font-size: 1.2rem; color: #94a3b8; }
    .info-mascota {
        display: flex;
        align-items: center;
        gap: 1rem;
        width: 100%;
    }
    .info-mascota h5 { margin: 0; color: #1e293b; font-weight: 700; }
    .info-mascota span { color: #64748b; font-size: 0.9rem; }
    .header-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .detalle-container {
        background: #fff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 1.5rem;
        min-height: 200px;
    }
    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
        color: #94a3b8;
    }
    .empty-state i {
        font-size: 3rem;
        display: block;
        margin-bottom: 0.75rem;
        color: #cbd5e1;
    }
    .empty-state h5 { color: #1e293b; margin-bottom: 0.25rem; }
    .empty-state p { margin: 0; font-size: 0.95rem; }
    .nav-tabs .nav-link {
        border: none;
        color: #64748b;
        font-weight: 500;
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        transition: 0.2s;
        margin-right: 0.25rem;
    }
    .nav-tabs .nav-link:hover {
        color: #1e293b;
        background: #f1f5f9;
    }
    .nav-tabs .nav-link.active {
        color: #4f46e5;
        background: #eef2ff;
        box-shadow: none;
    }
    .nav-tabs .nav-link i { margin-right: 0.4rem; }
    .tab-content .tab-pane {
        animation: fadeUp 0.3s ease;
    }
    @keyframes fadeUp {
        0% { opacity: 0; transform: translateY(8px); }
        100% { opacity: 1; transform: translateY(0); }
    }
    @media (max-width: 768px) {
        .avatar-pet { width: 44px; height: 44px; font-size: 1.2rem; }
        .consulta-item, .vacuna-item, .desparasitacion-item, .tratamiento-item { padding: 0.75rem 1rem; }
        .detalle-container { padding: 1rem; }
        .nav-tabs .nav-link { padding: 0.5rem 0.75rem; font-size: 0.85rem; }
    }
</style>

<div class="container-fluid py-3">
    <div class="p-4 mb-5 rounded-4" style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); box-shadow: 0 4px 20px rgba(74,108,247,0.10);">
        <div class="d-flex align-items-center gap-3">
            <!-- Icono -->
            <div class="header-icon">
                <i class="lni lni-heart"></i>
            </div>
            <!-- Texto -->
            <div>
                <h2 class="fw-bold mb-0" style="color:#0f172a;">Historia clínica</h2>
                <p class="text-secondary-light mb-0">Consulta el historial médico de tus mascotas.</p>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body p-4">
            <div class="row align-items-center g-4">
                <div class="col-lg-5">
                    <label class="form-label fw-semibold text-secondary-light mb-2">
                        <i class="lni lni-search-alt me-1"></i> Selecciona una mascota
                    </label>
                    <select id="mascota_id" class="form-select">
                        <option value="">Cargando mascotas...</option>
                    </select>
                </div>
                <div class="col-lg-7">
                    <div id="informacionMascota" class="info-placeholder">
                        <i class="lni lni-information"></i> Selecciona una mascota para ver su historial
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="contenidoHistoria"></div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function(){
        toastr.options = { closeButton: true, progressBar: true, positionClass: "toast-top-right", timeOut: 4000, extendedTimeOut: 1000 };
        cargarMascotas();
        $('#mascota_id').change(function(){
            const mascotaId = $(this).val();
            if(!mascotaId){
                $('#informacionMascota').html('<i class="lni lni-information"></i> Selecciona una mascota para ver su historial');
                $('#contenidoHistoria').html('');
                return;
            }
            const mascota = $(this).find('option:selected').data('mascota');
            $('#informacionMascota').html(`
                <div class="info-mascota">
                    <div class="avatar-pet"><i class="lni lni-home"></i></div>
                    <div>
                        <h5>${mascota.nombre}</h5>
                        <span>${mascota.especie}${mascota.raza ? ' · '+mascota.raza : ''}</span>
                    </div>
                </div>
            `);
            cargarHistoriaCompleta(mascotaId);
        });
    });

    function cargarMascotas(){
        $.ajax({
            url:"{{ route('historia-clinica.mascotas') }}",
            type:"GET",
            success:function(response){
                const select = $('#mascota_id');
                select.empty();
                if(!response.success || response.mascotas.length === 0){
                    select.append('<option value="">No tienes mascotas registradas</option>');
                    return;
                }
                select.append('<option value="">Seleccione una mascota</option>');
                $.each(response.mascotas, function(index, mascota){
                    const option = $('<option>', { value: mascota.id, text: mascota.nombre });
                    option.data('mascota', mascota);
                    select.append(option);
                });
            },
            error:function(xhr){
                console.error(xhr);
                $('#mascota_id').html('<option value="">Error al cargar mascotas</option>');
                toastr.error('No fue posible cargar tus mascotas.');
            }
        });
    }

    function cargarHistoriaCompleta(mascotaId){
        $('#contenidoHistoria').html(`
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="spinner-border" role="status"></div>
                    <p class="text-secondary-light mt-2 mb-0">Cargando información...</p>
                </div>
            </div>
        `);
        $.when(
            cargarConsultas(mascotaId),
            cargarVacunas(mascotaId),
            cargarDesparasitaciones(mascotaId),
            cargarTratamientos(mascotaId)
        ).done(function(consultasResp, vacunasResp, desparasitacionesResp, tratamientosResp){
            const consultas = consultasResp[0]?.consultas || [];
            const vacunas = vacunasResp[0]?.vacunas || [];
            const desparasitaciones = desparasitacionesResp[0]?.desparasitaciones || [];
            const tratamientos = tratamientosResp[0]?.tratamientos || [];
            renderTabs(consultas, vacunas, desparasitaciones, tratamientos);
        }).fail(function(){
            toastr.error('Error al cargar algunos datos.');
        });
    }

    function cargarConsultas(mascotaId){
        return $.ajax({
            url:"{{ route('historia-clinica.consultas',':id') }}".replace(':id', mascotaId),
            type:"GET"
        });
    }

    function cargarVacunas(mascotaId){
        return $.ajax({
            url:"{{ route('historia-clinica.vacunas',':id') }}".replace(':id', mascotaId),
            type:"GET"
        });
    }

    function cargarDesparasitaciones(mascotaId){
        return $.ajax({
            url:"{{ route('historia-clinica.desparasitaciones',':id') }}".replace(':id', mascotaId),
            type:"GET"
        });
    }

    function cargarTratamientos(mascotaId){
        return $.ajax({
            url:"{{ route('historia-clinica.tratamientos',':id') }}".replace(':id', mascotaId),
            type:"GET"
        });
    }

    function renderTabs(consultas, vacunas, desparasitaciones, tratamientos){
        const hasConsultas = consultas && consultas.length > 0;
        const hasVacunas = vacunas && vacunas.length > 0;
        const hasDesparasitaciones = desparasitaciones && desparasitaciones.length > 0;
        const hasTratamientos = tratamientos && tratamientos.length > 0;

        let tabsNav = '';
        let tabsContent = '';

        tabsNav += `<li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-consultas" data-bs-toggle="tab" data-bs-target="#consultas" type="button" role="tab">
                <i class="lni lni-list"></i> Consultas ${hasConsultas ? '<span class="badge bg-primary bg-opacity-10 text-primary ms-1 rounded-pill">'+consultas.length+'</span>' : ''}
            </button>
        </li>`;
        tabsContent += `<div class="tab-pane fade show active" id="consultas" role="tabpanel">
            ${hasConsultas ? renderLista(consultas, 'consulta') : '<div class="empty-state"><i class="lni lni-files"></i><h5>No hay consultas</h5><p>Esta mascota no tiene consultas registradas.</p></div>'}
        </div>`;

        tabsNav += `<li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-vacunas" data-bs-toggle="tab" data-bs-target="#vacunas" type="button" role="tab">
                <i class="lni lni-shield"></i> Vacunas ${hasVacunas ? '<span class="badge bg-primary bg-opacity-10 text-primary ms-1 rounded-pill">'+vacunas.length+'</span>' : ''}
            </button>
        </li>`;
        tabsContent += `<div class="tab-pane fade" id="vacunas" role="tabpanel">
            ${hasVacunas ? renderLista(vacunas, 'vacuna') : '<div class="empty-state"><i class="lni lni-shield"></i><h5>No hay vacunas</h5><p>Esta mascota no tiene vacunas registradas.</p></div>'}
        </div>`;

        tabsNav += `<li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-desparasitaciones" data-bs-toggle="tab" data-bs-target="#desparasitaciones" type="button" role="tab">
                <i class="lni lni-protection"></i> Desparasitaciones ${hasDesparasitaciones ? '<span class="badge bg-primary bg-opacity-10 text-primary ms-1 rounded-pill">'+desparasitaciones.length+'</span>' : ''}
            </button>
        </li>`;
        tabsContent += `<div class="tab-pane fade" id="desparasitaciones" role="tabpanel">
            ${hasDesparasitaciones ? renderLista(desparasitaciones, 'desparasitacion') : '<div class="empty-state"><i class="lni lni-protection"></i><h5>No hay desparasitaciones</h5><p>Esta mascota no tiene desparasitaciones registradas.</p></div>'}
        </div>`;

        tabsNav += `<li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-tratamientos" data-bs-toggle="tab" data-bs-target="#tratamientos" type="button" role="tab">
                <i class="lni lni-medical"></i> Tratamientos ${hasTratamientos ? '<span class="badge bg-primary bg-opacity-10 text-primary ms-1 rounded-pill">'+tratamientos.length+'</span>' : ''}
            </button>
        </li>`;
        tabsContent += `<div class="tab-pane fade" id="tratamientos" role="tabpanel">
            ${hasTratamientos ? renderLista(tratamientos, 'tratamiento') : '<div class="empty-state"><i class="lni lni-medical"></i><h5>No hay tratamientos</h5><p>Esta mascota no tiene tratamientos registrados.</p></div>'}
        </div>`;

        $('#contenidoHistoria').html(`
            <div class="card">
                <div class="card-body p-4">
                    <ul class="nav nav-tabs mb-4" id="historiaTabs" role="tablist" style="border-bottom: none; gap: 0.25rem; flex-wrap: nowrap; overflow-x: auto; padding-bottom: 0.25rem;">
                        ${tabsNav}
                    </ul>
                    <div class="tab-content">
                        ${tabsContent}
                    </div>
                </div>
            </div>
        `);

        $('.consulta-item, .vacuna-item, .desparasitacion-item, .tratamiento-item').click(function(){
            const tipo = $(this).data('tipo');
            const index = $(this).data('index');
            const container = $(this).closest('.tab-pane').find('.detalle-container');
            let data = [];
            if(tipo === 'consulta') data = consultas;
            else if(tipo === 'vacuna') data = vacunas;
            else if(tipo === 'desparasitacion') data = desparasitaciones;
            else if(tipo === 'tratamiento') data = tratamientos;
            const item = data[index];
            if(!item) return;
            $(this).closest('.tab-pane').find('.consulta-item, .vacuna-item, .desparasitacion-item, .tratamiento-item').removeClass('consulta-seleccionada vacuna-seleccionada desparasitacion-seleccionada tratamiento-seleccionado');
            $(this).addClass('consulta-seleccionada vacuna-seleccionada desparasitacion-seleccionada tratamiento-seleccionado');
            mostrarDetalle(item, tipo, container);
        });

        $('.consulta-item, .vacuna-item, .desparasitacion-item, .tratamiento-item').first().trigger('click');
    }

    function renderLista(items, tipo){
        let html = '<div class="row g-4"><div class="col-lg-6"><div>';
        $.each(items, function(index, item){
            let badge = '';
            let titulo = '';
            let fecha = '';
            if(tipo === 'consulta'){
                titulo = item.motivo;
                fecha = formatearFecha(item.fecha);
                badge = `<span class="badge-soft-primary"><i class="lni lni-calendar me-1"></i>${fecha}</span>`;
            } else if(tipo === 'vacuna'){
                titulo = item.nombre;
                fecha = formatearFecha(item.fecha_aplicacion);
                badge = `<span class="badge-soft-success"><i class="lni lni-calendar me-1"></i>${fecha}</span>`;
            } else if(tipo === 'desparasitacion'){
                titulo = item.producto;
                fecha = formatearFecha(item.fecha);
                badge = `<span class="badge-soft-primary"><i class="lni lni-calendar me-1"></i>${fecha}</span>`;
            } else if(tipo === 'tratamiento'){
                titulo = item.nombre;
                fecha = formatearFecha(item.fecha_inicio);
                let cls = 'badge-soft-primary';
                if(item.estado === 'activo') cls = 'badge-soft-success';
                else if(item.estado === 'finalizado') cls = 'badge-soft-secondary';
                else if(item.estado === 'cancelado') cls = 'badge-soft-danger';
                badge = `<span class="${cls}">${item.estado}</span>`;
            }
            const vet = item.veterinario ? item.veterinario.name : 'Veterinario no registrado';
            html += `
                <div class="${tipo}-item" data-tipo="${tipo}" data-index="${index}">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 fw-semibold" style="color:#0f172a;">${titulo}</h6>
                            <small class="text-secondary-light"><i class="lni lni-user me-1"></i>${vet}</small>
                        </div>
                        ${badge}
                    </div>
                </div>
            `;
        });
        html += '</div></div><div class="col-lg-6"><div class="detalle-container"><div class="empty-state"><i class="lni lni-pointer"></i><h5>Seleccione un elemento</h5><p>Seleccione un ítem de la lista para ver sus detalles.</p></div></div></div></div>';
        return html;
    }

    function mostrarDetalle(item, tipo, container){
        let html = '';
        if(tipo === 'consulta'){
            html = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1" style="color:#0f172a;">${item.motivo}</h5>
                        <span class="text-secondary-light" style="font-size:0.9rem;"><i class="lni lni-calendar me-1"></i>${formatearFecha(item.fecha)}</span>
                    </div>
                    <span class="badge-soft-primary">Consulta</span>
                </div>
                <div class="mb-3 detalle-bloque">
                    <small class="text-secondary-light d-block fw-semibold">Veterinario</small>
                    <strong style="color:#0f172a;">${item.veterinario ? item.veterinario.name : 'No registrado'}</strong>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #edf2f7;">
                            <small class="text-secondary-light d-block fw-semibold">Peso</small>
                            <strong style="color:#0f172a;">${item.peso ? item.peso+' kg' : 'No registrado'}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #edf2f7;">
                            <small class="text-secondary-light d-block fw-semibold">Temperatura</small>
                            <strong style="color:#0f172a;">${item.temperatura ? item.temperatura+' °C' : 'No registrada'}</strong>
                        </div>
                    </div>
                </div>
                <div class="mb-3 detalle-bloque">
                    <small class="text-secondary-light d-block fw-semibold">Diagnóstico</small>
                    <p class="mb-0" style="color:#0f172a;">${item.diagnostico || 'No registrado'}</p>
                </div>
                <div class="detalle-bloque">
                    <small class="text-secondary-light d-block fw-semibold">Observaciones</small>
                    <p class="mb-0" style="color:#0f172a;">${item.observaciones || 'Sin observaciones'}</p>
                </div>
            `;
        } else if(tipo === 'vacuna'){
            html = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1" style="color:#0f172a;">${item.nombre}</h5>
                        <span class="text-secondary-light" style="font-size:0.9rem;"><i class="lni lni-calendar me-1"></i>${formatearFecha(item.fecha_aplicacion)}</span>
                    </div>
                    <span class="badge-soft-success">Aplicada</span>
                </div>
                <div class="mb-3 detalle-bloque">
                    <small class="text-secondary-light d-block fw-semibold">Veterinario</small>
                    <strong style="color:#0f172a;">${item.veterinario ? item.veterinario.name : 'No registrado'}</strong>
                </div>
                <div class="mb-3 detalle-bloque">
                    <small class="text-secondary-light d-block fw-semibold">Fecha de aplicación</small>
                    <strong style="color:#0f172a;">${formatearFecha(item.fecha_aplicacion)}</strong>
                </div>
                <div class="mb-3 detalle-bloque">
                    <small class="text-secondary-light d-block fw-semibold">Próxima dosis</small>
                    <strong style="color:#0f172a;">${item.proxima_dosis ? formatearFecha(item.proxima_dosis) : 'No programada'}</strong>
                </div>
                <div class="detalle-bloque">
                    <small class="text-secondary-light d-block fw-semibold">Observaciones</small>
                    <p class="mb-0" style="color:#0f172a;">${item.observaciones || 'Sin observaciones'}</p>
                </div>
            `;
        } else if(tipo === 'desparasitacion'){
            html = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1" style="color:#0f172a;">${item.producto}</h5>
                        <span class="text-secondary-light" style="font-size:0.9rem;"><i class="lni lni-calendar me-1"></i>${formatearFecha(item.fecha)}</span>
                    </div>
                    <span class="badge-soft-primary">Desparasitación</span>
                </div>
                <div class="mb-3 detalle-bloque">
                    <small class="text-secondary-light d-block fw-semibold">Veterinario</small>
                    <strong style="color:#0f172a;">${item.veterinario ? item.veterinario.name : 'No registrado'}</strong>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #edf2f7;">
                            <small class="text-secondary-light d-block fw-semibold">Fecha</small>
                            <strong style="color:#0f172a;">${formatearFecha(item.fecha)}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #edf2f7;">
                            <small class="text-secondary-light d-block fw-semibold">Próxima fecha</small>
                            <strong style="color:#0f172a;">${item.proxima_fecha ? formatearFecha(item.proxima_fecha) : 'No programada'}</strong>
                        </div>
                    </div>
                </div>
                <div class="detalle-bloque">
                    <small class="text-secondary-light d-block fw-semibold">Observaciones</small>
                    <p class="mb-0" style="color:#0f172a;">${item.observaciones || 'Sin observaciones'}</p>
                </div>
            `;
        } else if(tipo === 'tratamiento'){
            let cls = 'badge-soft-primary';
            if(item.estado === 'activo') cls = 'badge-soft-success';
            else if(item.estado === 'finalizado') cls = 'badge-soft-secondary';
            else if(item.estado === 'cancelado') cls = 'badge-soft-danger';
            html = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-1" style="color:#0f172a;">${item.nombre}</h5>
                        <span class="text-secondary-light" style="font-size:0.9rem;"><i class="lni lni-calendar me-1"></i>${formatearFecha(item.fecha_inicio)}</span>
                    </div>
                    <span class="${cls}">${item.estado}</span>
                </div>
                <div class="mb-3 detalle-bloque">
                    <small class="text-secondary-light d-block fw-semibold">Veterinario</small>
                    <strong style="color:#0f172a;">${item.veterinario ? item.veterinario.name : 'No registrado'}</strong>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #edf2f7;">
                            <small class="text-secondary-light d-block fw-semibold">Fecha de inicio</small>
                            <strong style="color:#0f172a;">${formatearFecha(item.fecha_inicio)}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #edf2f7;">
                            <small class="text-secondary-light d-block fw-semibold">Fecha de finalización</small>
                            <strong style="color:#0f172a;">${item.fecha_fin ? formatearFecha(item.fecha_fin) : 'Sin fecha de finalización'}</strong>
                        </div>
                    </div>
                </div>
                <div class="mb-3 detalle-bloque">
                    <small class="text-secondary-light d-block fw-semibold">Descripción</small>
                    <p class="mb-0" style="color:#0f172a;">${item.descripcion || 'Sin descripción'}</p>
                </div>
                <div class="mb-3 detalle-bloque">
                    <small class="text-secondary-light d-block fw-semibold">Consulta relacionada</small>
                    <p class="mb-0" style="color:#0f172a;">${item.consulta ? item.consulta.motivo+' - '+formatearFecha(item.consulta.fecha) : 'Sin consulta relacionada'}</p>
                </div>
                <div class="detalle-bloque">
                    <small class="text-secondary-light d-block fw-semibold">Observaciones</small>
                    <p class="mb-0" style="color:#0f172a;">${item.observaciones || 'Sin observaciones'}</p>
                </div>
            `;
        }
        container.html(html);
    }

    function formatearFecha(fecha){
        const d = new Date(fecha);
        return String(d.getDate()).padStart(2,'0')+'/'+String(d.getMonth()+1).padStart(2,'0')+'/'+d.getFullYear();
    }
</script>
@endsection