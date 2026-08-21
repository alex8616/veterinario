@extends('layouts.my-dashboard-layout')

@section('content')
<link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    .agenda-header {
        background: linear-gradient(135deg, #f0f4ff 0%, #d9e2ff 100%);
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 8px 30px rgba(74, 108, 247, 0.12);
        margin-bottom: 2rem;
    }
    .agenda-header h2 {
        font-weight: 700;
        color: #1a2332;
        letter-spacing: -0.02em;
    }
    .agenda-header h2 i {
        color: #4a6cf7;
    }
    .agenda-header p {
        color: #5a6a7e;
        font-size: 0.95rem;
        margin-bottom: 0;
    }
    .avatar-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #4a6cf7, #7c3aed);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .calendario-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .calendario-header h4 {
        margin: 0;
        font-weight: 700;
        color: #1e293b;
        font-size: 1.25rem;
    }
    .calendario-nav {
        display: flex;
        gap: 0.5rem;
    }
    .calendario-nav button {
        border: 1px solid #e2e8f0;
        background: #fff;
        border-radius: 8px;
        padding: 0.4rem 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 500;
        color: #334155;
    }
    .calendario-nav button:hover {
        background: #f8fafc;
        border-color: #b3c6ff;
        color: #1e293b;
    }
    .calendario-semana, .calendario-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }
    .calendario-semana > div {
        text-align: center;
        font-weight: 600;
        color: #64748b;
        padding: 0.6rem 0.3rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .dia {
        min-height: 100px;
        border: 1px solid #edf2f7;
        padding: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
        background: #fff;
        border-radius: 4px;
        position: relative;
    }
    .dia:hover {
        background: #f8faff;
        border-color: #c7d6ff;
        z-index: 1;
    }
    .dia.otro-mes {
        background: #fafafa;
        color: #cbd5e1;
    }
    .dia.seleccionado {
        background: #eef2ff;
        border: 2px solid #4a6cf7;
        border-radius: 6px;
    }
    .dia-hoy .numero-dia {
        background: #4a6cf7;
        color: #fff;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 700;
    }
    .numero-dia {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
        font-size: 0.95rem;
    }
    .evento-cita {
        font-size: 0.7rem;
        background: #eef2ff;
        color: #4338ca;
        border-radius: 4px;
        padding: 2px 6px;
        margin-bottom: 2px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        font-weight: 500;
        transition: all 0.15s;
    }
    .evento-cita.cancelada {
        background: #fee2e2;
        color: #b91c1c;
    }
    .evento-cita.atendida {
        background: #dcfce7;
        color: #15803d;
    }
    .evento-cita:hover {
        filter: brightness(0.95);
    }
    .cita-dia-card {
        border: 1px solid #edf2f7;
        border-radius: 1rem;
        padding: 1rem 1.2rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s;
        background: #fff;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.8rem;
    }
    .cita-dia-card:hover {
        border-color: #c7d6ff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }
    .cita-dia-card .hora {
        font-weight: 700;
        color: #4a6cf7;
        font-size: 1.1rem;
        min-width: 70px;
    }
    .cita-dia-card .info {
        flex: 1;
    }
    .cita-dia-card .info .nombre {
        font-weight: 600;
        color: #1a2332;
    }
    .cita-dia-card .info .motivo {
        color: #6b7a8f;
        font-size: 0.9rem;
    }
    .cita-dia-card .badge-estado {
        font-weight: 500;
        font-size: 0.75rem;
        padding: 0.25rem 0.9rem;
        border-radius: 30px;
        text-transform: capitalize;
        background: #f0f2f5;
        color: #2c3e50;
    }
    .badge-estado.confirmada { background: #d4edda; color: #155724; }
    .badge-estado.atendida { background: #cce5ff; color: #004085; }
    .badge-estado.cancelada { background: #f8d7da; color: #721c24; }
    .badge-estado.pendiente { background: #fff3cd; color: #856404; }
    .vacio-icon {
        font-size: 2.5rem;
        color: #d0d9e6;
        display: block;
        margin-bottom: 0.8rem;
    }
    .detalle-card {
        background: #fafbfc;
        border-radius: 1rem;
        padding: 1.5rem;
        border: 1px solid #edf2f7;
    }
    .detalle-card .campo {
        background: #fff;
        border-radius: 0.75rem;
        padding: 0.8rem 1rem;
        border: 1px solid #edf2f7;
    }
    .detalle-card .campo .label {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: #8a9aa8;
        letter-spacing: 0.04em;
        font-weight: 600;
        display: block;
        margin-bottom: 0.15rem;
    }
    .detalle-card .campo .value {
        font-weight: 500;
        color: #1a2332;
    }
    .sub-card {
        background: #fff;
        border-radius: 1rem;
        padding: 1.2rem;
        border: 1px solid #edf2f7;
        height: 100%;
        transition: all 0.2s;
    }
    .sub-card:hover {
        border-color: #c7d6ff;
    }
    .sub-card .icon-box {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }
    .sub-card .icon-box.vacunas { background: #ecfdf5; color: #059669; }
    .sub-card .icon-box.desparasitaciones { background: #fff7ed; color: #ea580c; }
    .sub-card .icon-box.tratamientos { background: #eef2ff; color: #4a6cf7; }
    .item-mini {
        background: #f8fafc;
        border-radius: 0.6rem;
        padding: 0.6rem 0.8rem;
        margin-bottom: 0.5rem;
        border-left: 3px solid #4a6cf7;
    }
    .item-mini .titulo {
        font-weight: 600;
        color: #1a2332;
        font-size: 0.9rem;
    }
    .item-mini .detalle {
        font-size: 0.8rem;
        color: #6b7a8f;
    }
    @media (max-width: 768px) {
        .dia { min-height: 70px; padding: 0.3rem; }
        .calendario-semana > div { font-size: 0.7rem; padding: 0.3rem; }
        .cita-dia-card { flex-direction: column; align-items: stretch; }
        .cita-dia-card .hora { font-size: 1rem; }
        .agenda-header { padding: 1.5rem; }
    }
</style>

<div class="container-fluid py-3">
    <div class="agenda-header">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-icon"><i class="lni lni-calendar"></i></div>
            <div>
                <h2 class="fw-bold mb-0">Agenda veterinaria</h2>
                <p class="mb-0">Consulta y administra las citas programadas.</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <div id="calendario"></div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-1" style="color:#1e293b;">Citas del día</h5>
                    <span id="fechaSeleccionada" class="text-muted">Selecciona un día</span>
                </div>
                <span id="cantidadCitas" class="badge bg-light text-dark px-3 py-2 rounded-pill">0 citas</span>
            </div>
            <div id="listaCitasDia">
                <div class="text-center text-muted py-5">
                    <i class="lni lni-calendar vacio-icon"></i>
                    <h5 class="mt-2">Selecciona un día</h5>
                    <p class="mb-0">Aquí aparecerán las citas programadas.</p>
                </div>
            </div>
            <div id="contenedorDetalleCita" class="card mt-4 d-none">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold mb-1">Detalle de la cita</h5>
                            <span class="text-muted">Información de la cita seleccionada</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="cerrarDetalleCita()">
                            <i class="lni lni-close"></i> Cerrar
                        </button>
                    </div>
                    <div id="detalleCita"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
let citas = [];
let fechaCalendario = new Date();
let fechaSeleccionada = null;

$(document).ready(function(){
    toastr.options={closeButton:true,progressBar:true,positionClass:"toast-top-right",timeOut:4000};
    cargarCitas();
});

function cargarCitas(){
    $.ajax({
        url:"{{ route('veterinario.citas.data') }}",
        type:"GET",
        success:function(response){
            if(!response.success){ toastr.error('No fue posible cargar las citas.'); return; }
            citas=response.citas||[];
            mostrarCalendario();
        },
        error:function(xhr){
            console.error(xhr);
            toastr.error('No fue posible cargar la agenda.');
        }
    });
}

function mostrarCalendario(){
    const anio=fechaCalendario.getFullYear();
    const mes=fechaCalendario.getMonth();
    const primerDia=new Date(anio,mes,1);
    const ultimoDia=new Date(anio,mes+1,0);
    let primerDiaSemana=primerDia.getDay();
    if(primerDiaSemana===0) primerDiaSemana=6; else primerDiaSemana--;
    const cantidadDias=ultimoDia.getDate();
    const meses=['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    const hoy=new Date();
    const fechaHoy=`${hoy.getFullYear()}-${String(hoy.getMonth()+1).padStart(2,'0')}-${String(hoy.getDate()).padStart(2,'0')}`;
    let html=`
        <div class="calendario-header">
            <h4>${meses[mes]} ${anio}</h4>
            <div class="calendario-nav">
                <button type="button" onclick="mesAnterior()"><i class="lni lni-chevron-left"></i></button>
                <button type="button" onclick="irHoy()">Hoy</button>
                <button type="button" onclick="mesSiguiente()"><i class="lni lni-chevron-right"></i></button>
            </div>
        </div>
        <div class="calendario-semana">
            <div>Lun</div><div>Mar</div><div>Mié</div><div>Jue</div><div>Vie</div><div>Sáb</div><div>Dom</div>
        </div>
        <div class="calendario-grid">
    `;
    for(let i=0;i<primerDiaSemana;i++){
        html+=`<div class="dia otro-mes"></div>`;
    }
    for(let dia=1;dia<=cantidadDias;dia++){
        const fecha=`${anio}-${String(mes+1).padStart(2,'0')}-${String(dia).padStart(2,'0')}`;
        let clases='dia';
        if(fecha===fechaHoy) clases+=' dia-hoy';
        if(fecha===fechaSeleccionada) clases+=' seleccionado';
        const citasDelDia=citas.filter(c=>c.fecha?String(c.fecha).substring(0,10)===fecha:false);
        html+=`<div class="${clases}" onclick="seleccionarDia('${fecha}')"><div class="numero-dia">${dia}</div>`;
        citasDelDia.forEach(c=>{
            const estadoMap={confirmada:'confirmada',atendida:'atendida',cancelada:'cancelada'};
            const clase=estadoMap[c.estado]||'pendiente';
            const hora=c.hora?String(c.hora).substring(0,5):'--:--';
            const nombre=c.mascota&&c.mascota.nombre?c.mascota.nombre:'Mascota';
            html+=`<div class="evento-cita ${clase}" title="${c.motivo||'Cita'}"><strong>${hora}</strong> · ${nombre}</div>`;
        });
        html+=`</div>`;
    }
    html+=`</div>`;
    $('#calendario').html(html);
}

function seleccionarDia(fecha){
    fechaSeleccionada=fecha;
    mostrarCalendario();
    $('#fechaSeleccionada').text('Cargando...');
    $('#cantidadCitas').text('...');
    $('#listaCitasDia').html(`<div class="text-center text-muted py-5"><div class="spinner-border text-primary mb-3"></div><h5>Cargando citas...</h5></div>`);
    $.ajax({
        url:`/veterinario/agenda/${fecha}`,
        type:'GET',
        success:function(response){
            if(!response.success){ toastr.error('No fue posible cargar las citas.'); return; }
            mostrarCitasDiaDesdeRespuesta(fecha,response.citas||[]);
        },
        error:function(xhr){
            console.error(xhr);
            $('#listaCitasDia').html(`<div class="alert alert-danger">No fue posible cargar las citas de este día.</div>`);
            toastr.error('Error al cargar las citas.');
        }
    });
}

function mostrarCitasDiaDesdeRespuesta(fecha,citasDelDia){
    const fechaObj=new Date(fecha+'T00:00:00');
    const fechaTexto=fechaObj.toLocaleDateString('es-BO',{weekday:'long',day:'numeric',month:'long',year:'numeric'});
    $('#fechaSeleccionada').text(fechaTexto.charAt(0).toUpperCase()+fechaTexto.slice(1));
    $('#cantidadCitas').text(`${citasDelDia.length} ${citasDelDia.length===1?'cita':'citas'}`);
    if(citasDelDia.length===0){
        $('#listaCitasDia').html(`<div class="text-center text-muted py-5"><i class="lni lni-calendar vacio-icon"></i><h5 class="mt-2">No hay citas</h5><p class="mb-0">No tienes citas programadas para este día.</p></div>`);
        return;
    }
    let html='';
    citasDelDia.forEach(c=>{
        let estadoClase='pendiente';
        if(c.estado==='confirmada') estadoClase='confirmada';
        if(c.estado==='atendida') estadoClase='atendida';
        if(c.estado==='cancelada') estadoClase='cancelada';
        html+=`<div class="cita-dia-card">
            <div class="hora">${c.hora?c.hora.substring(0,5):'--:--'}</div>
            <div class="info">
                <div class="nombre">${c.mascota?c.mascota.nombre:'Mascota'}</div>
                <div class="motivo">${c.motivo||'Sin motivo'}</div>
            </div>
            <span class="badge-estado ${estadoClase}">${c.estado}</span>
            <button type="button" class="btn btn-sm btn-outline-primary" onclick="verCita(${c.id})">Ver detalle</button>
        </div>`;
    });
    $('#listaCitasDia').html(html);
}

function verCita(citaId){
    $('#contenedorDetalleCita').removeClass('d-none');
    $('#detalleCita').html(`<div class="text-center py-5"><div class="spinner-border text-primary mb-3"></div><h5>Cargando cita...</h5></div>`);
    $('html, body').animate({scrollTop:$('#contenedorDetalleCita').offset().top-20},400);
    $.ajax({
        url:`/veterinario/citas/${citaId}`,
        type:'GET',
        success:function(response){
            if(!response.success){ toastr.error(response.message||'No fue posible obtener la cita.'); return; }
            mostrarDetalleCita(response.cita);
        },
        error:function(xhr){
            console.error(xhr);
            const msg=xhr.responseJSON?.message||'No fue posible cargar el detalle.';
            $('#detalleCita').html(`<div class="alert alert-danger">${msg}</div>`);
            toastr.error(msg);
        }
    });
}

function mostrarDetalleCita(cita){
    const mascota=cita.mascota;
    let boton='';
    if(cita.estado==='cancelada'){
        boton=`<div class="alert alert-danger mb-0"><i class="lni lni-close me-1"></i> Esta cita fue cancelada.</div>`;
    }else if(cita.estado==='pendiente'||cita.estado==='confirmada'){
        boton=`<button type="button" class="btn btn-primary w-100" onclick="iniciarConsulta(${cita.id})"><i class="lni lni-stethoscope me-1"></i> Iniciar consulta</button>`;
    }else if(cita.estado==='atendida'){
        boton=`<div class="alert alert-success mb-3"><i class="lni lni-checkmark-circle me-1"></i> Esta cita ya fue atendida.</div><button type="button" class="btn btn-outline-primary w-100" onclick="verConsulta(${cita.id})"><i class="lni lni-eye me-1"></i> Ver consulta</button>`;
    }else{
        boton=`<div class="alert alert-secondary mb-0">Esta cita no está disponible.</div>`;
    }
    const estadoBadge=cita.estado==='cancelada'?'bg-danger':cita.estado==='atendida'?'bg-primary':cita.estado==='confirmada'?'bg-success':'bg-warning';
    $('#detalleCita').html(`<div class="detalle-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div><h4 class="fw-bold mb-1">${mascota?mascota.nombre:'Mascota'}</h4><span class="text-muted">${mascota?mascota.especie:''}${mascota&&mascota.raza?' · '+mascota.raza:''}</span></div>
            <span class="badge ${estadoBadge}">${cita.estado}</span>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-4"><div class="campo"><span class="label">Fecha</span><span class="value">${formatearFecha(cita.fecha)}</span></div></div>
            <div class="col-md-4"><div class="campo"><span class="label">Hora</span><span class="value">${cita.hora?cita.hora.substring(0,5):'--:--'}</span></div></div>
            <div class="col-md-4"><div class="campo"><span class="label">Especie</span><span class="value">${mascota?mascota.especie:'No registrada'}</span></div></div>
        </div>
        <div class="mb-3"><span class="label">Motivo</span><div class="value">${cita.motivo||'Sin motivo'}</div></div>
        <div class="mb-4"><span class="label">Observaciones</span><div class="value">${cita.observaciones||'Sin observaciones'}</div></div>
        ${boton}
    </div>`);
}

function cerrarDetalleCita(){
    $('#contenedorDetalleCita').addClass('d-none');
    $('#detalleCita').html('');
}

function formatearFecha(fecha){
    if(!fecha) return 'Sin fecha';
    const partes=String(fecha).substring(0,10).split('-');
    if(partes.length!==3) return 'Fecha inválida';
    const fechaObj=new Date(parseInt(partes[0]),parseInt(partes[1])-1,parseInt(partes[2]));
    if(isNaN(fechaObj.getTime())) return 'Fecha inválida';
    const texto=fechaObj.toLocaleDateString('es-BO',{weekday:'long',day:'numeric',month:'long',year:'numeric'});
    return texto.charAt(0).toUpperCase()+texto.slice(1);
}

function mesAnterior(){ fechaCalendario.setMonth(fechaCalendario.getMonth()-1); mostrarCalendario(); }
function mesSiguiente(){ fechaCalendario.setMonth(fechaCalendario.getMonth()+1); mostrarCalendario(); }
function irHoy(){
    fechaCalendario=new Date();
    mostrarCalendario();
    const hoy=new Date();
    const fechaHoy=`${hoy.getFullYear()}-${String(hoy.getMonth()+1).padStart(2,'0')}-${String(hoy.getDate()).padStart(2,'0')}`;
    seleccionarDia(fechaHoy);
}

function verConsulta(citaId){
    $('#contenedorDetalleCita').removeClass('d-none');
    $('#detalleCita').html(`<div class="text-center py-5"><div class="spinner-border text-primary mb-3"></div><h5>Cargando consulta...</h5></div>`);
    $('html, body').animate({scrollTop:$('#contenedorDetalleCita').offset().top-20},400);
    $.ajax({
        url:`/veterinario/citas/${citaId}/consulta`,
        type:'GET',
        success:function(response){
            if(!response.success){ toastr.error(response.message||'No fue posible cargar la consulta.'); return; }
            const consulta=response.consulta;
            const mascotaId=consulta.mascota_id;
            $.when(
                $.get(`/historia-clinica/vacunas/${mascotaId}`),
                $.get(`/historia-clinica/desparasitaciones/${mascotaId}`)
            ).done(function(vacRes,desRes){
                const vacunas=vacRes[0].vacunas||[];
                const desparasitaciones=desRes[0].desparasitaciones||[];
                mostrarConsultaAgenda(consulta,vacunas,desparasitaciones);
            }).fail(function(xhr){
                console.error(xhr);
                mostrarConsultaAgenda(consulta,[],[]);
            });
        },
        error:function(xhr){
            console.error(xhr);
            const msg=xhr.responseJSON?.message||'No fue posible cargar la consulta.';
            $('#detalleCita').html(`<div class="alert alert-danger">${msg}</div>`);
            toastr.error(msg);
        }
    });
}

function mostrarConsultaAgenda(consulta,vacunas=[],desparasitaciones=[]){
    const mascota=consulta.mascota;
    const tratamientos=consulta.tratamientos||[];
    let html=`<div class="detalle-card">
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="avatar-icon" style="width:48px;height:48px;background:#eef2ff;color:#4a6cf7;border-radius:50%;display:flex;align-items:center;justify-content:center;"><i class="lni lni-stethoscope"></i></div>
            <div><h4 class="fw-bold mb-0">Consulta veterinaria</h4><span class="text-muted">${mascota?mascota.nombre:'Mascota'}</span></div>
        </div>
        <div class="row g-3 mb-4">
            <div class="col-md-6"><div class="campo"><span class="label">Fecha</span><span class="value">${formatearFecha(consulta.fecha)}</span></div></div>
            <div class="col-md-6"><div class="campo"><span class="label">Veterinario</span><span class="value">${consulta.veterinario?consulta.veterinario.name:'No registrado'}</span></div></div>
        </div>
        <div class="mb-3"><span class="label">Motivo</span><div class="value">${consulta.motivo||'Sin motivo'}</div></div>
        <div class="mb-3"><span class="label">Diagnóstico</span><div class="value">${consulta.diagnostico||'Sin diagnóstico'}</div></div>
        <div class="row g-3 mb-4">
            <div class="col-md-6"><div class="campo"><span class="label">Peso</span><span class="value">${consulta.peso?consulta.peso+' kg':'No registrado'}</span></div></div>
            <div class="col-md-6"><div class="campo"><span class="label">Temperatura</span><span class="value">${consulta.temperatura?consulta.temperatura+' °C':'No registrada'}</span></div></div>
        </div>
        <div class="mb-4"><span class="label">Observaciones</span><div class="value">${consulta.observaciones||'Sin observaciones'}</div></div>
        <hr class="my-4">
        <div class="row g-3">
            <div class="col-lg-4"><div class="sub-card"><div class="d-flex align-items-center gap-2 mb-3"><div class="icon-box vacunas"><i class="lni lni-shield"></i></div><div><h5 class="fw-bold mb-0">Vacunas</h5><small class="text-muted">${vacunas.length} registradas</small></div></div>${vacunas.length===0?'<div class="text-center text-muted py-3"><i class="lni lni-checkmark-circle" style="font-size:2rem;"></i><p class="mt-2 mb-0">No hay vacunas.</p></div>':vacunas.slice(0,3).map(v=>`<div class="item-mini"><div class="titulo">${v.nombre}</div><div class="detalle">Aplicación: ${formatearFecha(v.fecha_aplicacion)}</div>${v.proxima_dosis?`<div class="detalle">Próxima: ${formatearFecha(v.proxima_dosis)}</div>`:''}</div>`).join('')}${vacunas.length>3?`<div class="text-muted small">+${vacunas.length-3} más</div>`:''}</div></div>
            <div class="col-lg-4"><div class="sub-card"><div class="d-flex align-items-center gap-2 mb-3"><div class="icon-box desparasitaciones"><i class="lni lni-bug"></i></div><div><h5 class="fw-bold mb-0">Desparasitaciones</h5><small class="text-muted">${desparasitaciones.length} registradas</small></div></div>${desparasitaciones.length===0?'<div class="text-center text-muted py-3"><i class="lni lni-checkmark-circle" style="font-size:2rem;"></i><p class="mt-2 mb-0">No hay registros.</p></div>':desparasitaciones.slice(0,3).map(d=>`<div class="item-mini"><div class="titulo">${d.producto}</div><div class="detalle">Fecha: ${formatearFecha(d.fecha)}</div>${d.proxima_fecha?`<div class="detalle">Próxima: ${formatearFecha(d.proxima_fecha)}</div>`:''}</div>`).join('')}${desparasitaciones.length>3?`<div class="text-muted small">+${desparasitaciones.length-3} más</div>`:''}</div></div>
            <div class="col-lg-4"><div class="sub-card"><div class="d-flex align-items-center gap-2 mb-3"><div class="icon-box tratamientos"><i class="lni lni-capsule"></i></div><div><h5 class="fw-bold mb-0">Tratamientos</h5><small class="text-muted">${tratamientos.length} registrados</small></div></div>${tratamientos.length===0?'<div class="text-center text-muted py-3"><i class="lni lni-checkmark-circle" style="font-size:2rem;"></i><p class="mt-2 mb-0">No hay tratamientos.</p></div>':tratamientos.slice(0,3).map(t=>`<div class="item-mini"><div class="titulo">${t.nombre}</div><div class="detalle">Inicio: ${formatearFecha(t.fecha_inicio)}</div><div class="detalle"><span class="badge ${t.estado==='activo'?'bg-success':'bg-secondary'}">${t.estado}</span></div></div>`).join('')}${tratamientos.length>3?`<div class="text-muted small">+${tratamientos.length-3} más</div>`:''}</div></div>
        </div>`;
    if(tratamientos.length>0){
        html+=`<hr class="my-4"><h5 class="fw-bold mb-3"><i class="lni lni-pills me-1"></i> Todos los tratamientos</h5>`;
        tratamientos.forEach(t=>{
            html+=`<div class="cita-dia-card" style="border-left:3px solid #4a6cf7;"><div style="flex:1;"><div class="nombre">${t.nombre}</div><div class="motivo">${t.descripcion||'Sin descripción'}</div><small class="text-muted">Inicio: ${formatearFecha(t.fecha_inicio)}${t.fecha_fin?' · Fin: '+formatearFecha(t.fecha_fin):''}</small></div><span class="badge-estado ${t.estado==='activo'?'confirmada':'cancelada'}">${t.estado}</span></div>`;
        });
    }
    html+=`</div>`;
    $('#detalleCita').html(html);
}
</script>
@endsection