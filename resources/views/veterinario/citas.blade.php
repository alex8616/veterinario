@extends('layouts.my-dashboard-layout')

@section('content')
<link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .citas-header {
        background: linear-gradient(135deg, #f0f4ff 0%, #d9e2ff 100%);
        border-radius: 1.5rem;
        padding: 2rem 2rem;
        box-shadow: 0 8px 30px rgba(74, 108, 247, 0.12);
        margin-bottom: 2rem;
        transition: all 0.2s;
    }
    .citas-header h2 {
        font-weight: 700;
        color: #1a2332;
        letter-spacing: -0.02em;
    }
    .citas-header h2 i {
        color: #4a6cf7;
    }
    .citas-header p {
        color: #5a6a7e;
        font-size: 0.95rem;
        margin-bottom: 0;
    }
    .cita-card {
        background: #ffffff;
        border-radius: 1rem;
        padding: 1.25rem 1.5rem;
        margin-bottom: 0.75rem;
        border: 1px solid #eef2f6;
        box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    .cita-card:hover {
        border-color: #b3c6ff;
        box-shadow: 0 6px 18px rgba(74, 108, 247, 0.08);
        transform: translateY(-2px);
    }
    .cita-card.cita-seleccionada {
        border-color: #4a6cf7;
        background: #f8faff;
        box-shadow: 0 6px 20px rgba(74, 108, 247, 0.15);
    }
    .cita-card .cita-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        flex: 1;
    }
    .cita-card .cita-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #eef2ff;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4a6cf7;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .cita-card .cita-mascota {
        font-weight: 600;
        color: #1a2332;
        font-size: 1.05rem;
    }
    .cita-card .cita-especie {
        font-size: 0.85rem;
        color: #6b7a8f;
        display: block;
    }
    .cita-card .cita-fecha-hora {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.9rem;
        color: #2c3e50;
        flex-wrap: wrap;
    }
    .cita-card .cita-fecha-hora i {
        color: #6b7a8f;
        margin-right: 0.3rem;
    }
    .cita-card .cita-estado {
        font-weight: 500;
        font-size: 0.8rem;
        padding: 0.3rem 0.9rem;
        border-radius: 20px;
        background: #f0f2f5;
        color: #2c3e50;
        text-transform: capitalize;
    }
    .cita-card .cita-estado.pendiente { background: #fff3cd; color: #856404; }
    .cita-card .cita-estado.confirmada { background: #d4edda; color: #155724; }
    .cita-card .cita-estado.cancelada { background: #f8d7da; color: #721c24; }

    .detalle-card {
        background: #ffffff;
        border-radius: 1rem;
        padding: 1.5rem 1.8rem;
        border: 1px solid #eef2f6;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        height: 100%;
        min-height: 320px;
        transition: all 0.2s;
    }
    .detalle-card .detalle-vacio {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 280px;
        color: #8a9aa8;
    }
    .detalle-card .detalle-vacio i {
        font-size: 3rem;
        color: #d0d9e6;
        margin-bottom: 1rem;
    }
    .detalle-card .detalle-vacio h5 {
        color: #2c3e50;
        font-weight: 600;
    }
    .detalle-item {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f2f4f8;
    }
    .detalle-item:last-of-type {
        border-bottom: none;
    }
    .detalle-item i {
        color: #4a6cf7;
        width: 1.4rem;
        text-align: center;
        font-size: 1rem;
    }
    .detalle-item .label {
        color: #6b7a8f;
        font-size: 0.85rem;
        min-width: 80px;
    }
    .detalle-item .value {
        font-weight: 500;
        color: #1a2332;
    }
    .btn-iniciar {
        background: #4a6cf7;
        border: none;
        border-radius: 0.75rem;
        padding: 0.7rem 1.5rem;
        font-weight: 600;
        color: white;
        transition: all 0.2s;
        width: 100%;
        margin-top: 1.2rem;
    }
    .btn-iniciar:hover {
        background: #3b5de7;
        box-shadow: 0 8px 20px rgba(74, 108, 247, 0.3);
        transform: translateY(-2px);
    }
    .btn-iniciar i {
        margin-right: 0.5rem;
    }
    .spinner-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 0;
    }
    .spinner-wrapper .spinner-border {
        width: 3rem;
        height: 3rem;
        color: #4a6cf7;
    }
    .tab-pane .card-header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .tab-pane .card-header-actions h6 {
        margin: 0;
        font-weight: 600;
        color: #4a5a6e;
        letter-spacing: 0.02em;
    }
    /* Estilos elegantes para los items de las pestañas */
    .item-card {
        background: #ffffff;
        border-radius: 0.9rem;
        padding: 1.2rem 1.4rem;
        margin-bottom: 0.8rem;
        border: 1px solid #eef2f6;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
    }
    .item-card:hover {
        border-color: #d0d9e6;
        box-shadow: 0 4px 16px rgba(0,0,0,0.04);
        transform: translateY(-1px);
    }
    .item-card .item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.6rem;
    }
    .item-card .item-title {
        font-weight: 600;
        color: #1a2332;
        font-size: 1rem;
        margin-bottom: 0.15rem;
    }
    .item-card .item-subtitle {
        font-size: 0.8rem;
        color: #6b7a8f;
    }
    .item-card .item-badge {
        font-weight: 500;
        font-size: 0.7rem;
        padding: 0.25rem 0.8rem;
        border-radius: 30px;
        text-transform: capitalize;
        letter-spacing: 0.02em;
        background: #f0f2f5;
        color: #2c3e50;
        white-space: nowrap;
    }
    .item-card .item-badge.activo { background: #d4edda; color: #155724; }
    .item-card .item-badge.completado { background: #cce5ff; color: #004085; }
    .item-card .item-badge.suspendido { background: #f8d7da; color: #721c24; }
    .item-card .item-badge.aplicada { background: #d4edda; color: #155724; }
    .item-card .item-badge.aplicado { background: #fff3cd; color: #856404; }
    .item-card .item-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.4rem 1.2rem;
        margin-top: 0.3rem;
    }
    .item-card .item-body .field {
        display: flex;
        flex-direction: column;
    }
    .item-card .item-body .field .label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #8a9aa8;
        letter-spacing: 0.04em;
        font-weight: 600;
    }
    .item-card .item-body .field .value {
        font-size: 0.9rem;
        color: #1a2332;
        font-weight: 500;
    }
    .item-card .item-body .field .value.sin-dato {
        color: #b0c0d0;
        font-weight: 400;
    }
    .item-card .item-observacion {
        margin-top: 0.6rem;
        padding-top: 0.6rem;
        border-top: 1px dashed #edf2f7;
        font-size: 0.85rem;
        color: #4a5a6e;
    }
    .item-card .item-observacion .label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #8a9aa8;
        font-weight: 600;
        display: block;
        margin-bottom: 0.1rem;
    }
    .item-vacio {
        text-align: center;
        padding: 2.5rem 0;
        color: #8a9aa8;
        background: #fafbfc;
        border-radius: 1rem;
        border: 1px dashed #dce3ec;
    }
    .item-vacio i {
        font-size: 2.8rem;
        color: #d0d9e6;
        margin-bottom: 0.8rem;
        display: block;
    }
    .item-vacio p {
        margin-bottom: 0;
        font-size: 0.95rem;
        font-weight: 400;
    }
    @media (max-width: 768px) {
        .citas-header { padding: 1.5rem; }
        .cita-card { flex-direction: column; align-items: stretch; padding: 1rem; }
        .cita-card .cita-info { flex-wrap: wrap; }
        .cita-card .cita-fecha-hora { font-size: 0.85rem; }
        .detalle-card { margin-top: 1rem; min-height: auto; }
        .item-card .item-body {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid py-3">
    <div class="citas-header">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div>
                <h2><i class="fas fa-user-md me-2"></i>Mis citas</h2>
                <p>Consulta y gestiona las citas que tienes asignadas</p>
            </div>
            <div class="mt-2 mt-md-0">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                    <i class="fas fa-calendar-check me-1"></i> Veterinario
                </span>
            </div>
        </div>
    </div>

    <div id="contenedorCitas">
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="text-muted mt-3 mb-0">Cargando tus citas...</p>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$(document).ready(function(){ cargarCitasVeterinario(); });

function cargarCitasVeterinario(){
    $.ajax({
        url: "{{ route('veterinario.citas.data') }}",
        type: "GET",
        success: function(response){
            if(!response.success){ toastr.error('No fue posible cargar las citas.'); return; }
            mostrarCitasVeterinario(response.citas);
        },
        error: function(xhr){
            console.error(xhr);
            $('#contenedorCitas').html(`
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-exclamation-triangle text-danger" style="font-size:2.5rem;"></i>
                        <h5 class="mt-3">Error al cargar las citas</h5>
                        <p class="text-muted mb-0">Intenta nuevamente más tarde.</p>
                    </div>
                </div>
            `);
            toastr.error('Error al cargar las citas.');
        }
    });
}

function mostrarCitasVeterinario(citas){
    if(!citas || citas.length === 0){
        $('#contenedorCitas').html(`
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-times" style="font-size:2.5rem; color:#b0c0d0;"></i>
                    <h5 class="mt-3">No tienes citas asignadas</h5>
                    <p class="text-muted mb-0">Actualmente no tienes citas pendientes.</p>
                </div>
            </div>
        `);
        return;
    }
    let html = `
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card h-100" style="border: none; border-radius: 1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
                    <div class="card-body p-3">
                        <h6 class="text-muted text-uppercase small fw-semibold mb-3"><i class="fas fa-list-ul me-1"></i> Lista de citas</h6>
                        <div id="listaCitas" style="max-height: 600px; overflow-y: auto; padding-right: 5px;">
    `;
    $.each(citas, function(index, cita){
        let estadoClass = '', estadoText = '';
        switch(cita.estado){
            case 'pendiente':   estadoClass = 'pendiente'; estadoText = 'Pendiente'; break;
            case 'confirmada':  estadoClass = 'confirmada'; estadoText = 'Confirmada'; break;
            case 'cancelada':   estadoClass = 'cancelada'; estadoText = 'Cancelada'; break;
            default: estadoClass = ''; estadoText = cita.estado;
        }
        const fechaFormateada = formatearFecha(cita.fecha);
        const nombreMascota = cita.mascota ? cita.mascota.nombre : '—';
        const especie = cita.mascota ? cita.mascota.especie : '';
        const raza = cita.mascota && cita.mascota.raza ? ' · ' + cita.mascota.raza : '';
        html += `
            <div class="cita-card" data-id="${cita.id}">
                <div class="cita-info">
                    <div class="cita-avatar"><i class="fas fa-paw"></i></div>
                    <div>
                        <span class="cita-mascota">${nombreMascota}</span>
                        <span class="cita-especie">${especie}${raza}</span>
                    </div>
                </div>
                <div class="cita-fecha-hora">
                    <span><i class="fas fa-calendar-day"></i> ${fechaFormateada}</span>
                    <span><i class="fas fa-clock"></i> ${cita.hora}</span>
                </div>
                <span class="cita-estado ${estadoClass}">${estadoText}</span>
            </div>
        `;
    });
    html += `
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="detalle-card" id="detalleCita">
                    <div class="detalle-vacio">
                        <i class="fas fa-hand-pointer"></i>
                        <h5>Selecciona una cita</h5>
                        <p class="text-muted mb-0">Haz clic en cualquier cita de la lista para ver sus detalles.</p>
                    </div>
                </div>
            </div>
        </div>
    `;
    $('#contenedorCitas').html(html);
    $('.cita-card').click(function(){
        const citaId = $(this).data('id');
        $('.cita-card').removeClass('cita-seleccionada');
        $(this).addClass('cita-seleccionada');
        cargarDetalleCita(citaId);
    });
}

function formatearFecha(fecha){
    const date = new Date(fecha);
    const dia = String(date.getDate()).padStart(2,'0');
    const mes = String(date.getMonth()+1).padStart(2,'0');
    const anio = date.getFullYear();
    return `${dia}/${mes}/${anio}`;
}

function cargarDetalleCita(citaId){
    $('#detalleCita').html(`
        <div class="spinner-wrapper">
            <div class="spinner-border" role="status"><span class="visually-hidden">Cargando...</span></div>
            <p class="text-muted mt-3 mb-0">Cargando detalles...</p>
        </div>
    `);
    $.ajax({
        url: "{{ route('veterinario.citas.detalle',':id') }}".replace(':id', citaId),
        type: "GET",
        success: function(response){
            if(!response.success){ toastr.error(response.message || 'No fue posible cargar la cita.'); return; }
            mostrarDetalleCita(response.cita);
        },
        error: function(xhr){
            console.error(xhr);
            toastr.error('No fue posible cargar la información de la cita.');
            $('#detalleCita').html(`
                <div class="detalle-vacio">
                    <i class="fas fa-exclamation-circle text-danger"></i>
                    <h5>Error al cargar</h5>
                    <p class="text-muted mb-0">Intenta nuevamente.</p>
                </div>
            `);
        }
    });
}

function mostrarDetalleCita(cita){
    const mascota = cita.mascota;
    let boton = '';
    if(cita.estado === 'cancelada'){
        boton = `<div class="alert alert-danger mb-0"><i class="lni lni-close me-1"></i> Esta cita fue cancelada y no puede iniciar una consulta.</div>`;
    }else if(cita.estado === 'pendiente' || cita.estado === 'confirmada'){
        boton = `<button type="button" class="btn btn-primary w-100" onclick="iniciarConsulta(${cita.id})"><i class="lni lni-stethoscope me-1"></i> Iniciar consulta</button>`;
    }else if(cita.estado === 'atendida'){
        boton = `
            <div class="alert alert-success mb-3"><i class="lni lni-checkmark-circle me-1"></i> Esta cita ya fue atendida.</div>
            <button type="button" class="btn btn-primary w-100 mb-3" onclick="mostrarConsultaExistente(${cita.id})"><i class="lni lni-eye me-1"></i> Ver consulta</button>
            <div class="row g-2">
                <div class="col-md-4"><button type="button" class="btn btn-outline-primary w-100" onclick="abrirTratamientoPorCita(${cita.id})"><i class="lni lni-medicine me-1"></i> Tratamiento</button></div>
                <div class="col-md-4"><button type="button" class="btn btn-outline-success w-100" onclick="abrirVacunaPorCita(${cita.id})"><i class="lni lni-heart me-1"></i> Vacuna</button></div>
                <div class="col-md-4"><button type="button" class="btn btn-outline-warning w-100" onclick="abrirDesparasitacionPorCita(${cita.id})"><i class="lni lni-shield me-1"></i> Desparasitación</button></div>
            </div>
        `;
    }else{
        boton = `<div class="alert alert-secondary mb-0">Esta cita no está disponible para iniciar una consulta.</div>`;
    }
    $('#detalleCita').html(`
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">${mascota ? mascota.nombre : 'Mascota'}</h4>
                <span class="text-muted">${mascota ? mascota.especie : ''}${mascota && mascota.raza ? ' · '+mascota.raza : ''}</span>
            </div>
            <span class="badge ${cita.estado==='cancelada'?'bg-danger':cita.estado==='confirmada'?'bg-success':cita.estado==='atendida'?'bg-primary':'bg-warning'}">${cita.estado}</span>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-6"><div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #edf2f7;"><small class="text-muted d-block">Fecha</small><strong>${formatearFecha(cita.fecha)}</strong></div></div>
            <div class="col-md-6"><div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #edf2f7;"><small class="text-muted d-block">Hora</small><strong>${cita.hora}</strong></div></div>
        </div>
        <div class="mb-3"><small class="text-muted d-block">Motivo</small><strong>${cita.motivo}</strong></div>
        <div class="mb-3"><small class="text-muted d-block">Sexo</small><strong>${mascota ? mascota.sexo : 'No registrado'}</strong></div>
        <div class="mb-4"><small class="text-muted d-block">Observaciones</small><p class="mb-0">${cita.observaciones || 'Sin observaciones'}</p></div>
        ${boton}
    `);
}

function iniciarConsulta(citaId){
    $.ajax({
        url: "{{ route('veterinario.citas.consulta',':id') }}".replace(':id', citaId),
        type: "GET",
        success: function(response){
            if(!response.success){ toastr.error(response.message || 'No se puede iniciar la consulta.'); return; }
            mostrarFormularioConsulta(response.cita);
        },
        error: function(xhr){
            console.error(xhr);
            let mensaje = 'No se puede iniciar la consulta.';
            if(xhr.responseJSON && xhr.responseJSON.message) mensaje = xhr.responseJSON.message;
            toastr.error(mensaje);
        }
    });
}

function mostrarFormularioConsulta(cita){
    const mascota = cita.mascota;
    $('#detalleCita').html(`
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div><h4 class="fw-bold mb-1">Nueva consulta</h4><span class="text-muted">${mascota.nombre} · ${mascota.especie}</span></div>
            <span class="badge bg-primary">Consulta</span>
        </div>
        <form id="formConsulta">
            <input type="hidden" name="cita_id" value="${cita.id}">
            <input type="hidden" name="mascota_id" value="${mascota.id}">
            <div class="mb-3"><label class="form-label fw-semibold">Motivo</label><input type="text" name="motivo" class="form-control" value="${cita.motivo || ''}" maxlength="255" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Diagnóstico</label><textarea name="diagnostico" class="form-control" rows="3" placeholder="Ingrese el diagnóstico..."></textarea></div>
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="form-label fw-semibold">Peso (kg)</label><input type="number" name="peso" class="form-control" step="0.01" min="0"></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Temperatura (°C)</label><input type="number" name="temperatura" class="form-control" step="0.1" min="0"></div>
            </div>
            <div class="mb-4"><label class="form-label fw-semibold">Observaciones</label><textarea name="observaciones" class="form-control" rows="4" placeholder="Observaciones de la consulta..."></textarea></div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-light w-50" onclick="cargarDetalleCita(${cita.id})">Cancelar</button>
                <button type="submit" class="btn btn-primary w-50" id="btnGuardarConsulta"><i class="lni lni-save me-1"></i> Guardar consulta</button>
            </div>
        </form>
    `);
    $('#formConsulta').submit(function(e){ e.preventDefault(); guardarConsulta(); });
}

function guardarConsulta(){
    const form = document.getElementById('formConsulta');
    const formData = new FormData(form);
    const boton = document.getElementById('btnGuardarConsulta');
    boton.disabled = true;
    boton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
        url: "{{ route('veterinario.citas.consulta.guardar',':id') }}".replace(':id', formData.get('cita_id')),
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response){
            toastr.success('Consulta registrada correctamente.');
            mostrarConsultaExistentePorId(response.consulta.id);
        },
        error: function(xhr){
            console.error(xhr);
            let mensaje = 'No fue posible registrar la consulta.';
            if(xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors){
                $.each(xhr.responseJSON.errors, function(campo, errores){ mensaje = errores[0]; return false; });
            }else if(xhr.responseJSON && xhr.responseJSON.message){
                mensaje = xhr.responseJSON.message;
            }
            toastr.error(mensaje);
        },
        complete: function(){
            boton.disabled = false;
            boton.innerHTML = '<i class="lni lni-save me-1"></i> Guardar consulta';
        }
    });
}

function mostrarConsultaExistente(citaId){
    $('#detalleCita').html(`
        <div class="text-center py-5">
            <div class="spinner-border"></div>
            <p class="text-muted mt-2 mb-0">Cargando consulta...</p>
        </div>
    `);
    $.ajax({
        url: "{{ route('veterinario.citas.consulta',':id') }}".replace(':id', citaId),
        type: "GET",
        success: function(response){
            if(!response.success){ toastr.error(response.message || 'No fue posible cargar la consulta.'); return; }
            mostrarDetalleConsultaVeterinario(response.consulta);
        },
        error: function(xhr){
            console.error(xhr);
            toastr.error(xhr.responseJSON?.message || 'No fue posible cargar la consulta.');
        }
    });
}

function mostrarDetalleConsultaVeterinario(consulta){
    // Construir listado de tratamientos con estilo mejorado
    let tratamientosHTML = '';
    if(!consulta.tratamientos || consulta.tratamientos.length === 0){
        tratamientosHTML = `
            <div class="item-vacio">
                <i class="lni lni-prescription"></i>
                <p>Esta consulta todavía no tiene tratamientos.</p>
            </div>
        `;
    }else{
        $.each(consulta.tratamientos, function(index, tratamiento){
            const estadoClase = tratamiento.estado || 'activo';
            tratamientosHTML += `
                <div class="item-card">
                    <div class="item-header">
                        <div>
                            <div class="item-title">${tratamiento.nombre}</div>
                            <div class="item-subtitle">${tratamiento.descripcion || 'Sin descripción'}</div>
                        </div>
                        <span class="item-badge ${estadoClase}">${estadoClase}</span>
                    </div>
                    <div class="item-body">
                        <div class="field">
                            <span class="label">Inicio</span>
                            <span class="value">${formatearFecha(tratamiento.fecha_inicio)}</span>
                        </div>
                        <div class="field">
                            <span class="label">Finalización</span>
                            <span class="value ${!tratamiento.fecha_fin ? 'sin-dato' : ''}">${tratamiento.fecha_fin ? formatearFecha(tratamiento.fecha_fin) : 'No definida'}</span>
                        </div>
                    </div>
                    ${tratamiento.observaciones ? `
                    <div class="item-observacion">
                        <span class="label">Observaciones</span>
                        ${tratamiento.observaciones}
                    </div>
                    ` : ''}
                </div>
            `;
        });
    }

    const consultaId = consulta.id;
    const mascotaId = consulta.mascota_id;

    $('#detalleCita').html(`
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">${consulta.motivo}</h4>
                <span class="text-muted"><i class="lni lni-calendar me-1"></i> ${formatearFecha(consulta.fecha)}</span>
            </div>
            <span class="badge bg-primary">Consulta</span>
        </div>
        <div class="mb-3"><small class="text-muted d-block">Veterinario</small><strong>${consulta.veterinario ? consulta.veterinario.name : 'No registrado'}</strong></div>
        <div class="row g-3 mb-3">
            <div class="col-md-6"><div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #edf2f7;"><small class="text-muted d-block">Peso</small><strong>${consulta.peso ? consulta.peso + ' kg' : 'No registrado'}</strong></div></div>
            <div class="col-md-6"><div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid #edf2f7;"><small class="text-muted d-block">Temperatura</small><strong>${consulta.temperatura ? consulta.temperatura + ' °C' : 'No registrada'}</strong></div></div>
        </div>
        <div class="mb-3"><small class="text-muted d-block">Diagnóstico</small><p class="mb-0">${consulta.diagnostico || 'No registrado'}</p></div>
        <div class="mb-4"><small class="text-muted d-block">Observaciones</small><p class="mb-0">${consulta.observaciones || 'Sin observaciones'}</p></div>

        <!-- Tabs -->
        <ul class="nav nav-tabs mt-4" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tratamientos-tab" data-bs-toggle="tab" data-bs-target="#tratamientos" type="button" role="tab" aria-controls="tratamientos" aria-selected="true">Tratamientos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="vacunas-tab" data-bs-toggle="tab" data-bs-target="#vacunas" type="button" role="tab" aria-controls="vacunas" aria-selected="false">Vacunas</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="desparasitaciones-tab" data-bs-toggle="tab" data-bs-target="#desparasitaciones" type="button" role="tab" aria-controls="desparasitaciones" aria-selected="false">Desparasitaciones</button>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tratamientos" role="tabpanel" aria-labelledby="tratamientos-tab">
                <div class="card-header-actions mt-3">
                    <h6><i class="lni lni-prescription me-1"></i> Lista de tratamientos</h6>
                    <button type="button" class="btn btn-primary btn-sm" onclick="abrirTratamiento(${consultaId}, ${mascotaId})"><i class="lni lni-plus me-1"></i> Nuevo tratamiento</button>
                </div>
                ${tratamientosHTML}
            </div>
            <div class="tab-pane fade" id="vacunas" role="tabpanel" aria-labelledby="vacunas-tab">
                <div class="card-header-actions mt-3">
                    <h6><i class="lni lni-shield me-1"></i> Vacunas aplicadas</h6>
                    <button type="button" class="btn btn-success btn-sm" onclick="abrirVacuna(${consultaId})"><i class="lni lni-plus me-1"></i> Nueva vacuna</button>
                </div>
                <div id="listaVacunasConsulta">Cargando vacunas...</div>
            </div>
            <div class="tab-pane fade" id="desparasitaciones" role="tabpanel" aria-labelledby="desparasitaciones-tab">
                <div class="card-header-actions mt-3">
                    <h6><i class="lni lni-medical me-1"></i> Desparasitaciones registradas</h6>
                    <button type="button" class="btn btn-warning btn-sm" onclick="abrirDesparasitacion(${consultaId})"><i class="lni lni-plus me-1"></i> Nueva desparasitación</button>
                </div>
                <div id="listaDesparasitacionesConsulta">Cargando desparasitaciones...</div>
            </div>
        </div>
    `);

    // Cargar vacunas y desparasitaciones con el nuevo estilo
    cargarVacunasConsulta(mascotaId);
    cargarDesparasitacionesConsulta(mascotaId);
}

function mostrarConsultaExistentePorId(consultaId){
    $('#detalleCita').html(`
        <div class="text-center py-5">
            <div class="spinner-border"></div>
            <p class="text-muted mt-2 mb-0">Cargando consulta...</p>
        </div>
    `);
    $.ajax({
        url: "{{ route('veterinario.consultas.show',':id') }}".replace(':id', consultaId),
        type: "GET",
        success: function(response){
            if(!response.success){ toastr.error(response.message || 'No fue posible cargar la consulta.'); return; }
            mostrarDetalleConsultaVeterinario(response.consulta);
        },
        error: function(xhr){
            console.error(xhr);
            toastr.error(xhr.responseJSON?.message || 'No fue posible cargar la consulta.');
        }
    });
}

function abrirTratamiento(consultaId, mascotaId){
    $('#detalleCita').html(`
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div><h4 class="fw-bold mb-1">Nuevo tratamiento</h4><span class="text-muted">Registrar tratamiento</span></div>
            <span class="badge bg-primary">Tratamiento</span>
        </div>
        <form id="formTratamiento">
            <div class="mb-3"><label class="form-label fw-semibold">Nombre</label><input type="text" name="nombre" class="form-control" placeholder="Ej. Amoxicilina" required></div>
            <div class="mb-3"><label class="form-label fw-semibold">Descripción</label><textarea name="descripcion" class="form-control" rows="3" placeholder="Indique el tratamiento..."></textarea></div>
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="form-label fw-semibold">Fecha de inicio</label><input type="date" name="fecha_inicio" class="form-control" value="${new Date().toISOString().split('T')[0]}" required></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Fecha de finalización</label><input type="date" name="fecha_fin" class="form-control"></div>
            </div>
            <div class="mb-3"><label class="form-label fw-semibold">Estado</label><select name="estado" class="form-select" required><option value="activo">Activo</option><option value="completado">Completado</option><option value="suspendido">Suspendido</option></select></div>
            <div class="mb-4"><label class="form-label fw-semibold">Observaciones</label><textarea name="observaciones" class="form-control" rows="3"></textarea></div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-light w-50" onclick="mostrarConsultaExistentePorId(${consultaId})">Cancelar</button>
                <button type="submit" class="btn btn-primary w-50"><i class="lni lni-save me-1"></i> Guardar tratamiento</button>
            </div>
        </form>
    `);
    $('#formTratamiento').submit(function(e){ e.preventDefault(); guardarTratamiento(consultaId); });
}

function guardarTratamiento(consultaId){
    const form = $('#formTratamiento');
    const btn = form.find('button[type="submit"]');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
        url: "{{ route('veterinario.consultas.tratamiento.guardar',':id') }}".replace(':id', consultaId),
        type: "POST",
        data: form.serialize(),
        success: function(response){
            if(!response.success){ toastr.error(response.message || 'No fue posible registrar el tratamiento.'); btn.prop('disabled', false).html('<i class="lni lni-save me-1"></i> Guardar tratamiento'); return; }
            toastr.success(response.message);
            mostrarConsultaExistentePorId(consultaId);
        },
        error: function(xhr){
            console.error(xhr);
            if(xhr.status===422 && xhr.responseJSON?.errors){
                $.each(xhr.responseJSON.errors, function(campo, mensajes){ toastr.error(mensajes[0]); });
            }else{
                toastr.error(xhr.responseJSON?.message || 'No fue posible registrar el tratamiento.');
            }
            btn.prop('disabled', false).html('<i class="lni lni-save me-1"></i> Guardar tratamiento');
        }
    });
}

function abrirVacuna(consultaId){
    $('#detalleCita').html(`
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div><h4 class="fw-bold mb-1">Nueva vacuna</h4><span class="text-muted">Registrar aplicación de vacuna</span></div>
            <span class="badge bg-success">Vacuna</span>
        </div>
        <form id="formVacuna">
            <div class="mb-3"><label class="form-label fw-semibold">Nombre de la vacuna</label><input type="text" name="nombre" class="form-control" placeholder="Ej. Rabia" required></div>
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="form-label fw-semibold">Fecha de aplicación</label><input type="date" name="fecha_aplicacion" class="form-control" value="${new Date().toISOString().split('T')[0]}" required></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Próxima dosis</label><input type="date" name="proxima_dosis" class="form-control"></div>
            </div>
            <div class="mb-4"><label class="form-label fw-semibold">Observaciones</label><textarea name="observaciones" class="form-control" rows="4" placeholder="Observaciones sobre la vacuna..."></textarea></div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-light w-50" onclick="mostrarConsultaExistentePorId(${consultaId})">Cancelar</button>
                <button type="submit" class="btn btn-success w-50"><i class="lni lni-save me-1"></i> Guardar vacuna</button>
            </div>
        </form>
    `);
    $('#formVacuna').submit(function(e){ e.preventDefault(); guardarVacuna(consultaId); });
}

function guardarVacuna(consultaId){
    const form = $('#formVacuna');
    const btn = form.find('button[type="submit"]');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
        url: "{{ route('veterinario.consultas.vacuna.guardar', ':id') }}".replace(':id', consultaId),
        type: "POST",
        data: form.serialize(),
        success: function(response){
            if(!response.success){ toastr.error(response.message || 'No fue posible registrar la vacuna.'); btn.prop('disabled', false).html('<i class="lni lni-save me-1"></i> Guardar vacuna'); return; }
            toastr.success(response.message);
            mostrarConsultaExistentePorId(consultaId);
        },
        error: function(xhr){
            console.error(xhr);
            if(xhr.status===422 && xhr.responseJSON?.errors){
                $.each(xhr.responseJSON.errors, function(campo, mensajes){ toastr.error(mensajes[0]); });
            }else{
                toastr.error(xhr.responseJSON?.message || 'No fue posible registrar la vacuna.');
            }
            btn.prop('disabled', false).html('<i class="lni lni-save me-1"></i> Guardar vacuna');
        }
    });
}

function cargarVacunasConsulta(mascotaId){
    $.ajax({
        url: "{{ route('veterinario.mascotas.vacunas', ':id') }}".replace(':id', mascotaId),
        type: "GET",
        success: function(response){
            if(!response.success){ $('#listaVacunasConsulta').html('<div class="item-vacio"><i class="lni lni-shield"></i><p>No fue posible cargar las vacunas.</p></div>'); return; }
            mostrarVacunasConsulta(response.vacunas);
        },
        error: function(xhr){
            console.error(xhr);
            $('#listaVacunasConsulta').html('<div class="item-vacio"><i class="lni lni-shield"></i><p>No fue posible cargar las vacunas.</p></div>');
        }
    });
}

function mostrarVacunasConsulta(vacunas){
    if(!vacunas || vacunas.length === 0){
        $('#listaVacunasConsulta').html(`
            <div class="item-vacio">
                <i class="lni lni-shield"></i>
                <p>No hay vacunas registradas para esta mascota.</p>
            </div>
        `);
        return;
    }
    let html = '';
    $.each(vacunas, function(index, vacuna){
        html += `
            <div class="item-card">
                <div class="item-header">
                    <div>
                        <div class="item-title">${vacuna.nombre}</div>
                        <div class="item-subtitle">Aplicada el ${formatearFecha(vacuna.fecha_aplicacion)}</div>
                    </div>
                    <span class="item-badge aplicada">Aplicada</span>
                </div>
                <div class="item-body">
                    <div class="field">
                        <span class="label">Próxima dosis</span>
                        <span class="value ${!vacuna.proxima_dosis ? 'sin-dato' : ''}">${vacuna.proxima_dosis ? formatearFecha(vacuna.proxima_dosis) : 'No programada'}</span>
                    </div>
                </div>
                ${vacuna.observaciones ? `
                <div class="item-observacion">
                    <span class="label">Observaciones</span>
                    ${vacuna.observaciones}
                </div>
                ` : ''}
            </div>
        `;
    });
    $('#listaVacunasConsulta').html(html);
}

function abrirDesparasitacion(consultaId){
    $('#detalleCita').html(`
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div><h4 class="fw-bold mb-1">Nueva desparasitación</h4><span class="text-muted">Registrar desparasitación</span></div>
            <span class="badge bg-warning text-dark">Desparasitación</span>
        </div>
        <form id="formDesparasitacion">
            <div class="mb-3"><label class="form-label fw-semibold">Producto</label><input type="text" name="producto" class="form-control" placeholder="Ej. Drontal Plus" required></div>
            <div class="row g-3 mb-3">
                <div class="col-md-6"><label class="form-label fw-semibold">Fecha</label><input type="date" name="fecha" class="form-control" value="${new Date().toISOString().split('T')[0]}" required></div>
                <div class="col-md-6"><label class="form-label fw-semibold">Próxima fecha</label><input type="date" name="proxima_fecha" class="form-control"></div>
            </div>
            <div class="mb-4"><label class="form-label fw-semibold">Observaciones</label><textarea name="observaciones" class="form-control" rows="4" placeholder="Observaciones..."></textarea></div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-light w-50" onclick="mostrarConsultaExistentePorId(${consultaId})">Cancelar</button>
                <button type="submit" class="btn btn-warning w-50"><i class="lni lni-save me-1"></i> Guardar</button>
            </div>
        </form>
    `);
    $('#formDesparasitacion').submit(function(e){ e.preventDefault(); guardarDesparasitacion(consultaId); });
}

function guardarDesparasitacion(consultaId){
    const form = $('#formDesparasitacion');
    const btn = form.find('button[type="submit"]');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
        url: "{{ route('veterinario.consultas.desparasitacion.guardar', ':id') }}".replace(':id', consultaId),
        type: "POST",
        data: form.serialize(),
        success: function(response){
            if(!response.success){ toastr.error(response.message || 'No fue posible registrar la desparasitación.'); btn.prop('disabled', false).html('<i class="lni lni-save me-1"></i> Guardar'); return; }
            toastr.success(response.message);
            mostrarConsultaExistentePorId(consultaId);
        },
        error: function(xhr){
            console.error(xhr);
            if(xhr.status===422 && xhr.responseJSON?.errors){
                $.each(xhr.responseJSON.errors, function(campo, mensajes){ toastr.error(mensajes[0]); });
            }else{
                toastr.error(xhr.responseJSON?.message || 'No fue posible registrar la desparasitación.');
            }
            btn.prop('disabled', false).html('<i class="lni lni-save me-1"></i> Guardar');
        }
    });
}

function cargarDesparasitacionesConsulta(mascotaId){
    $.ajax({
        url: "{{ route('veterinario.mascotas.desparasitaciones', ':id') }}".replace(':id', mascotaId),
        type: "GET",
        success: function(response){
            if(!response.success){ $('#listaDesparasitacionesConsulta').html('<div class="item-vacio"><i class="lni lni-medical"></i><p>No fue posible cargar las desparasitaciones.</p></div>'); return; }
            mostrarDesparasitacionesConsulta(response.desparasitaciones);
        },
        error: function(xhr){
            console.error(xhr);
            $('#listaDesparasitacionesConsulta').html('<div class="item-vacio"><i class="lni lni-medical"></i><p>No fue posible cargar las desparasitaciones.</p></div>');
        }
    });
}

function mostrarDesparasitacionesConsulta(desparasitaciones){
    if(!desparasitaciones || desparasitaciones.length === 0){
        $('#listaDesparasitacionesConsulta').html(`
            <div class="item-vacio">
                <i class="lni lni-medical"></i>
                <p>No hay desparasitaciones registradas para esta mascota.</p>
            </div>
        `);
        return;
    }
    let html = '';
    $.each(desparasitaciones, function(index, desparasitacion){
        html += `
            <div class="item-card">
                <div class="item-header">
                    <div>
                        <div class="item-title">${desparasitacion.producto}</div>
                        <div class="item-subtitle">Aplicado el ${formatearFecha(desparasitacion.fecha)}</div>
                    </div>
                    <span class="item-badge aplicado">Aplicado</span>
                </div>
                <div class="item-body">
                    <div class="field">
                        <span class="label">Próxima fecha</span>
                        <span class="value ${!desparasitacion.proxima_fecha ? 'sin-dato' : ''}">${desparasitacion.proxima_fecha ? formatearFecha(desparasitacion.proxima_fecha) : 'No programada'}</span>
                    </div>
                    <div class="field">
                        <span class="label">Veterinario</span>
                        <span class="value">${desparasitacion.veterinario ? desparasitacion.veterinario.name : 'No registrado'}</span>
                    </div>
                </div>
                ${desparasitacion.observaciones ? `
                <div class="item-observacion">
                    <span class="label">Observaciones</span>
                    ${desparasitacion.observaciones}
                </div>
                ` : ''}
            </div>
        `;
    });
    $('#listaDesparasitacionesConsulta').html(html);
}
</script>
@endsection