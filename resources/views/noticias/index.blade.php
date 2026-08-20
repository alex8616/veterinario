@extends('layouts.my-dashboard-layout')

@section('content')
<link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .card {
        border: none;
        border-radius: 24px;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .card:hover {
        box-shadow: 0 12px 40px rgba(0,0,0,0.06);
        transform: translateY(-3px);
    }

    .noticia-item {
        border: none;
        border-radius: 24px;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        transition: all 0.35s cubic-bezier(0.22, 1, 0.36, 1);
        cursor: pointer;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .noticia-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 60px rgba(79,70,229,0.10);
    }
    .noticia-item .card-img-top {
        height: 220px;
        object-fit: cover;
        transition: transform 0.6s ease;
        position: relative;
    }
    .noticia-item:hover .card-img-top {
        transform: scale(1.05);
    }
    .noticia-item .card-body {
        padding: 1.5rem 1.5rem 0.75rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .noticia-item .card-body h5 {
        color: #0f172a;
        font-weight: 700;
        font-size: 1.1rem;
        line-height: 1.4;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .noticia-item .card-body p {
        color: #475569;
        font-size: 0.9rem;
        line-height: 1.6;
        flex: 1;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 1rem;
    }
    .noticia-item .card-footer {
        background: transparent;
        border-top: 1px solid #f1f5f9;
        padding: 0.75rem 1.5rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .noticia-item .card-footer small {
        color: #94a3b8;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }
    .noticia-item .card-footer small i {
        font-size: 0.9rem;
    }

    .badge-category {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: rgba(79,70,229,0.9);
        backdrop-filter: blur(4px);
        color: #fff;
        font-weight: 500;
        padding: 0.25rem 0.9rem;
        border-radius: 50px;
        font-size: 0.7rem;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .badge-date {
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(4px);
        color: #0f172a;
        font-weight: 500;
        padding: 0.25rem 0.9rem;
        border-radius: 50px;
        font-size: 0.7rem;
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        border: 1px solid rgba(255,255,255,0.3);
    }

    .avatar-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.5rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(79,70,229,0.25);
    }

    .text-secondary-light { color: #64748b; }

    .noticia-contenido {
        color: #1e293b;
        line-height: 1.9;
        font-size: 1.05rem;
    }
    .noticia-contenido p { margin-bottom: 1rem; }

    .detalle-bloque {
        background: #f8fafc;
        border-radius: 16px;
        padding: 1rem 1.5rem;
        border-left: 4px solid #4f46e5;
        margin-bottom: 1rem;
    }
    .detalle-bloque:last-child { margin-bottom: 0; }

    .comentario-item {
        background: #f8fafc;
        border-radius: 16px;
        padding: 1rem 1.25rem;
        margin-bottom: 0.75rem;
        transition: all 0.2s ease;
        border: 1px solid #f1f5f9;
    }
    .comentario-item:hover {
        background: #f1f5f9;
        border-color: #e2e8f0;
    }
    .comentario-item .comentario-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.4rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    .comentario-item .comentario-header strong {
        color: #0f172a;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .comentario-item .comentario-header strong .avatar-mini {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .comentario-item .comentario-header small {
        color: #94a3b8;
        font-size: 0.75rem;
    }
    .comentario-item p {
        color: #334155;
        margin: 0;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    .btn-soft-danger {
        background: transparent;
        border: none;
        color: #94a3b8;
        padding: 0.2rem 0.5rem;
        border-radius: 8px;
        transition: 0.2s;
        font-size: 0.9rem;
    }
    .btn-soft-danger:hover {
        background: #fee2e2;
        color: #dc2626;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #94a3b8;
    }
    .empty-state i {
        font-size: 3.5rem;
        display: block;
        margin-bottom: 0.75rem;
        color: #cbd5e1;
    }
    .empty-state h5 { color: #0f172a; margin-bottom: 0.25rem; font-weight: 600; }
    .empty-state p { margin: 0; font-size: 0.95rem; }

    .spinner-border { color: #4f46e5; }

    #btnVolverNoticias {
        background: #f1f5f9;
        border: none;
        border-radius: 50px;
        padding: 0.4rem 1.2rem;
        font-weight: 500;
        color: #475569;
        transition: 0.2s;
        font-size: 0.85rem;
    }
    #btnVolverNoticias:hover {
        background: #e2e8f0;
        color: #0f172a;
        transform: translateX(-3px);
    }

    .btn-primary {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border: none;
        border-radius: 50px;
        padding: 0.5rem 1.8rem;
        font-weight: 500;
        transition: all 0.25s ease;
        color: #fff;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(79,70,229,0.30);
        color: #fff;
    }
    .btn-primary:active { transform: scale(0.97); }

    .btn-outline-danger {
        border: 1.5px solid #f43f5e;
        color: #f43f5e;
        border-radius: 50px;
        padding: 0.4rem 1.2rem;
        transition: 0.2s;
        background: transparent;
        font-weight: 500;
    }
    .btn-outline-danger:hover {
        background: #f43f5e;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(244,63,94,0.25);
    }
    .btn-danger {
        background: #f43f5e;
        border: none;
        border-radius: 50px;
        padding: 0.4rem 1.2rem;
        color: #fff;
        transition: 0.2s;
        font-weight: 500;
    }
    .btn-danger:hover {
        background: #e11d48;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(244,63,94,0.25);
    }

    .form-control {
        border-radius: 16px;
        border: 1.5px solid #e2e8f0;
        padding: 0.7rem 1rem;
        background: #f8fafc;
        transition: 0.2s;
        font-size: 0.95rem;
    }
    .form-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79,70,229,0.10);
        background: #fff;
    }
    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    .img-placeholder {
        height: 220px;
        background: linear-gradient(135deg, #eef2ff, #f8fafc);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 3rem;
    }
    .img-placeholder i {
        opacity: 0.4;
    }

    .image-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 24px 24px 0 0;
    }
    .image-wrapper img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        transition: transform 0.6s ease;
    }
    .noticia-item:hover .image-wrapper img {
        transform: scale(1.05);
    }

    .comentario-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    @media (max-width: 768px) {
        .avatar-icon { width: 40px; height: 40px; font-size: 1.2rem; }
        .noticia-item .card-img-top { height: 180px; }
        .noticia-item .card-body { padding: 1.25rem 1.25rem 0.5rem; }
        .noticia-item .card-footer { padding: 0.5rem 1.25rem 1rem; }
        .image-wrapper img { height: 180px; }
        .badge-category { font-size: 0.6rem; padding: 0.15rem 0.7rem; }
        .badge-date { font-size: 0.6rem; padding: 0.15rem 0.7rem; }
    }
</style>

<div class="container-fluid py-3">
    <div class="mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-icon"><i class="lni lni-bullhorn"></i></div>
            <div>
                <h2 class="fw-bold mb-0" style="color:#0f172a;">Noticias</h2>
                <p class="text-secondary-light mb-0">Información y novedades de nuestra veterinaria.</p>
            </div>
        </div>
    </div>

    <div id="contenedorNoticias">
        <div class="text-center py-5">
            <div class="spinner-border" role="status"></div>
            <p class="text-secondary-light mt-2 mb-0">Cargando noticias...</p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$(document).ready(function(){
    toastr.options = { closeButton: true, progressBar: true, positionClass: "toast-top-right", timeOut: 4000, extendedTimeOut: 1000 };
    cargarNoticias();
});

function cargarNoticias(){
    $.ajax({
        url:"{{ route('noticias.data') }}",
        type:"GET",
        success:function(response){
            if(!response.success){
                toastr.error('No fue posible cargar las noticias.');
                return;
            }
            mostrarNoticias(response.noticias);
        },
        error:function(xhr){
            console.error(xhr);
            $('#contenedorNoticias').html(`
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="empty-state">
                            <i class="lni lni-sad"></i>
                            <h5>No fue posible cargar las noticias</h5>
                            <p>Intenta nuevamente más tarde.</p>
                        </div>
                    </div>
                </div>
            `);
            toastr.error('Error al cargar las noticias.');
        }
    });
}

function mostrarNoticias(noticias){
    if(!noticias||noticias.length===0){
        $('#contenedorNoticias').html(`
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="empty-state">
                        <i class="lni lni-files"></i>
                        <h5>No hay noticias disponibles</h5>
                        <p>Actualmente no hay noticias publicadas.</p>
                    </div>
                </div>
            </div>
        `);
        return;
    }

    let html='<div class="row g-4">';
    $.each(noticias,function(index,noticia){
        const categoria = noticia.categoria || 'General';
        const fecha = formatearFecha(noticia.created_at);
        html+=`
            <div class="col-md-6 col-xl-4">
                <div class="noticia-item" data-id="${noticia.id}">
                    <div class="image-wrapper">
                        ${noticia.imagen ? `
                            <img src="${noticia.imagen}" alt="${noticia.titulo}">
                        ` : `
                            <div class="img-placeholder"><i class="lni lni-image"></i></div>
                        `}
                        <span class="badge-category"><i class="lni lni-tag"></i> ${categoria}</span>
                        <span class="badge-date"><i class="lni lni-calendar"></i> ${fecha}</span>
                    </div>
                    <div class="card-body">
                        <h5>${noticia.titulo}</h5>
                        <p>${resumirTexto(noticia.contenido, 120)}</p>
                    </div>
                    <div class="card-footer">
                        <small><i class="lni lni-user"></i> ${noticia.user ? noticia.user.name : 'Veterinaria'}</small>
                        <small><i class="lni lni-comments"></i> ${noticia.comentarios ? noticia.comentarios.length : 0}</small>
                    </div>
                </div>
            </div>
        `;
    });
    html+='</div>';
    $('#contenedorNoticias').html(html);

    $('.noticia-item').click(function(){
        const noticiaId=$(this).data('id');
        cargarNoticia(noticiaId);
    });
}

function cargarNoticia(noticiaId){
    $('#contenedorNoticias').html(`
        <div class="card">
            <div class="card-body text-center py-5">
                <div class="spinner-border" role="status"></div>
                <p class="text-secondary-light mt-2 mb-0">Cargando noticia...</p>
            </div>
        </div>
    `);

    $.ajax({
        url:"{{ route('noticias.show',':id') }}".replace(':id',noticiaId),
        type:"GET",
        success:function(response){
            if(!response.success){
                toastr.error('No fue posible cargar la noticia.');
                return;
            }
            mostrarDetalleNoticia(response.noticia, response.liked, response.totalLikes);
        },
        error:function(xhr){
            console.error(xhr);
            toastr.error('No fue posible cargar la noticia.');
            cargarNoticias();
        }
    });
}

function mostrarDetalleNoticia(noticia, liked, totalLikes){
    window.noticiaActualId = noticia.id;

    $('#contenedorNoticias').html(`
        <div class="card">
            <div class="card-body p-4 p-xl-5">
                <button type="button" class="btn btn-sm" id="btnVolverNoticias">
                    <i class="lni lni-arrow-left"></i> Volver a noticias
                </button>

                ${noticia.imagen ? `
                    <div class="my-4 rounded-4 overflow-hidden shadow-sm">
                        <img src="${noticia.imagen}" class="img-fluid w-100" style="max-height:450px;object-fit:cover;">
                    </div>
                ` : ''}

                <div class="mb-4">
                    <h2 class="fw-bold mb-2" style="color:#0f172a;">${noticia.titulo}</h2>
                    <div class="d-flex flex-wrap gap-3 text-secondary-light small">
                        <span><i class="lni lni-user me-1"></i> ${noticia.user ? noticia.user.name : 'Veterinaria'}</span>
                        <span><i class="lni lni-calendar me-1"></i> ${formatearFecha(noticia.created_at)}</span>
                        ${noticia.categoria ? `<span><i class="lni lni-tag me-1"></i> ${noticia.categoria}</span>` : ''}
                    </div>
                </div>

                <div class="noticia-contenido mb-4">
                    ${noticia.contenido}
                </div>

                <hr class="my-4">

                <div class="d-flex flex-wrap align-items-center gap-3">
                    <button type="button" class="btn ${liked ? 'btn-danger' : 'btn-outline-danger'}" id="btnLikeNoticia">
                        <i class="lni lni-heart me-1"></i> <span id="textoLike">${liked ? 'Te gusta' : 'Me gusta'}</span>
                    </button>
                    <span class="text-secondary-light small"><i class="lni lni-heart me-1"></i> <span id="totalLikes">${totalLikes}</span></span>
                    <span class="text-secondary-light small"><i class="lni lni-comments me-1"></i> ${noticia.comentarios ? noticia.comentarios.length : 0} Comentarios</span>
                </div>

                <div id="seccionComentarios" class="mt-4">
                    <h5 class="fw-bold mb-3" style="color:#0f172a;"><i class="lni lni-comments me-2"></i>Comentarios</h5>

                    <div class="mb-3">
                        <textarea id="nuevoComentario" class="form-control" rows="3" maxlength="1000" placeholder="Escribe un comentario..."></textarea>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-primary btn-sm" id="btnEnviarComentario">
                                <i class="lni lni-send me-1"></i> Comentar
                            </button>
                        </div>
                    </div>

                    <div id="listaComentarios">
                        <div class="text-center text-muted py-3">
                            <div class="spinner-border spinner-border-sm"></div>
                            <span class="ms-2">Cargando comentarios...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `);

    cargarComentarios(noticia.id);

    $('#btnEnviarComentario').click(function(){
        guardarComentario(noticia.id);
    });

    $('#btnVolverNoticias').click(function(){
        cargarNoticias();
    });

    $('#btnLikeNoticia').click(function(){
        cambiarLike(noticia.id);
    });
}

function cargarComentarios(noticiaId){
    $('#listaComentarios').html(`
        <div class="text-center text-muted py-3">
            <div class="spinner-border spinner-border-sm"></div>
            <span class="ms-2">Cargando comentarios...</span>
        </div>
    `);

    $.ajax({
        url:"{{ route('noticias.comentarios',':id') }}".replace(':id',noticiaId),
        type:"GET",
        success:function(response){
            if(!response.success){
                toastr.error('No fue posible cargar los comentarios.');
                return;
            }
            mostrarComentarios(response.comentarios);
        },
        error:function(xhr){
            console.error(xhr);
            toastr.error('No fue posible cargar los comentarios.');
        }
    });
}

function mostrarComentarios(comentarios){
    if(!comentarios||comentarios.length===0){
        $('#listaComentarios').html(`
            <div class="empty-state">
                <i class="lni lni-comments"></i>
                <h5>Todavía no hay comentarios</h5>
                <p>Sé el primero en comentar.</p>
            </div>
        `);
        return;
    }

    let html='';
    $.each(comentarios,function(index,comentario){
        const esPropio = Number(comentario.user_id) === Number({{ auth()->id() }});
        const nombre = comentario.user ? comentario.user.name : 'Usuario';
        const inicial = nombre.charAt(0).toUpperCase();
        html+=`
            <div class="comentario-item" data-id="${comentario.id}">
                <div class="comentario-header">
                    <strong>
                        <span class="comentario-avatar">${inicial}</span>
                        ${nombre}
                    </strong>
                    <div class="d-flex align-items-center gap-2">
                        <small>${formatearFecha(comentario.created_at)}</small>
                        ${esPropio ? `
                            <button type="button" class="btn-soft-danger btn-eliminar-comentario" data-id="${comentario.id}" title="Eliminar comentario">
                                <i class="lni lni-trash-can"></i>
                            </button>
                        ` : ''}
                    </div>
                </div>
                <p>${comentario.contenido}</p>
            </div>
        `;
    });
    $('#listaComentarios').html(html);

    $('.btn-eliminar-comentario').click(function(){
        const comentarioId=$(this).data('id');
        if(confirm('¿Seguro que deseas eliminar este comentario?')){
            eliminarComentario(comentarioId);
        }
    });
}

function eliminarComentario(comentarioId){
    const boton = $(`.btn-eliminar-comentario[data-id="${comentarioId}"]`);
    boton.prop('disabled',true);

    $.ajaxSetup({
        headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.ajax({
        url:"{{ route('noticias.comentarios.eliminar',['noticia'=>':noticia','comentario'=>':comentario']) }}"
            .replace(':noticia', window.noticiaActualId)
            .replace(':comentario', comentarioId),
        type:"DELETE",
        success:function(response){
            if(!response.success){
                toastr.error(response.message || 'No fue posible eliminar el comentario.');
                return;
            }
            $(`.comentario-item[data-id="${comentarioId}"]`).fadeOut(300, function(){
                $(this).remove();
                if($('#listaComentarios .comentario-item').length === 0){
                    $('#listaComentarios').html(`
                        <div class="empty-state">
                            <i class="lni lni-comments"></i>
                            <h5>Todavía no hay comentarios</h5>
                            <p>Sé el primero en comentar.</p>
                        </div>
                    `);
                }
            });
            toastr.success('Comentario eliminado correctamente.');
        },
        error:function(xhr){
            console.error(xhr);
            toastr.error(xhr.status === 403 ? 'No puedes eliminar este comentario.' : 'No fue posible eliminar el comentario.');
        },
        complete:function(){
            boton.prop('disabled',false);
        }
    });
}

function guardarComentario(noticiaId){
    const textarea = $('#nuevoComentario');
    const contenido = textarea.val().trim();
    const boton = $('#btnEnviarComentario');

    if(!contenido){
        toastr.warning('Escribe un comentario antes de enviarlo.');
        textarea.focus();
        return;
    }

    boton.prop('disabled',true);
    boton.html('<span class="spinner-border spinner-border-sm me-1"></span> Publicando...');

    $.ajaxSetup({
        headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.ajax({
        url:"{{ route('noticias.comentarios.guardar',':id') }}".replace(':id',noticiaId),
        type:"POST",
        data:{ contenido: contenido },
        success:function(response){
            if(!response.success){
                toastr.error(response.message || 'No fue posible publicar el comentario.');
                return;
            }
            textarea.val('');
            agregarComentario(response.comentario);
            toastr.success('Comentario publicado.');
        },
        error:function(xhr){
            console.error(xhr);
            if(xhr.status === 422 && xhr.responseJSON?.errors){
                const mensaje = Object.values(xhr.responseJSON.errors).flat().join(' ');
                toastr.error(mensaje);
            }else{
                toastr.error('No fue posible publicar el comentario.');
            }
        },
        complete:function(){
            boton.prop('disabled',false);
            boton.html('<i class="lni lni-send me-1"></i> Comentar');
        }
    });
}

function agregarComentario(comentario){
    if($('#listaComentarios .empty-state').length){
        $('#listaComentarios').html('');
    }

    const nombre = comentario.user ? comentario.user.name : 'Usuario';
    const inicial = nombre.charAt(0).toUpperCase();

    $('#listaComentarios').prepend(`
        <div class="comentario-item" data-id="${comentario.id}">
            <div class="comentario-header">
                <strong>
                    <span class="comentario-avatar">${inicial}</span>
                    ${nombre}
                </strong>
                <small>Ahora</small>
            </div>
            <p>${comentario.contenido}</p>
        </div>
    `);
}

function cambiarLike(noticiaId){
    const boton = $('#btnLikeNoticia');
    boton.prop('disabled',true);

    $.ajaxSetup({
        headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.ajax({
        url:"{{ route('noticias.like',':id') }}".replace(':id',noticiaId),
        type:"POST",
        success:function(response){
            if(!response.success){
                toastr.error(response.message || 'No fue posible actualizar el like.');
                return;
            }
            $('#totalLikes').text(response.totalLikes);
            if(response.liked){
                boton.removeClass('btn-outline-danger').addClass('btn-danger');
                $('#textoLike').text('Te gusta');
            }else{
                boton.removeClass('btn-danger').addClass('btn-outline-danger');
                $('#textoLike').text('Me gusta');
            }
        },
        error:function(xhr){
            console.error(xhr);
            toastr.error('No fue posible actualizar el like.');
        },
        complete:function(){
            boton.prop('disabled',false);
        }
    });
}

function resumirTexto(texto, longitud){
    if(!texto) return '';
    if(texto.length <= longitud) return texto;
    return texto.substring(0, longitud) + '...';
}

function formatearFecha(fecha){
    const d = new Date(fecha);
    return String(d.getDate()).padStart(2,'0')+'/'+String(d.getMonth()+1).padStart(2,'0')+'/'+d.getFullYear();
}
</script>
@endsection