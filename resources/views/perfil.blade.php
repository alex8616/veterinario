@extends('layouts.my-dashboard-layout')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid py-4">
    <div class="p-4 mb-5 rounded-4" style="background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); box-shadow: 0 4px 20px rgba(74,108,247,0.10);">
        <div class="d-flex flex-wrap align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold mb-1" style="color: #1a2332;">
                    <i class="fas fa-user-circle me-2" style="color: #4a6cf7;"></i>Mi perfil
                </h2>
                <p class="text-muted mb-0 fs-6">Gestiona tu información personal y configuración de cuenta</p>
            </div>
            <div class="mt-2 mt-sm-0">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                    <i class="fas fa-calendar-check me-1"></i> Miembro desde <span id="infoFechaBadge">—</span>
                </span>
            </div>
        </div>
    </div>

    <div id="loadingPerfil" class="text-center py-5">
        <div class="spinner-border text-primary" style="width: 3.5rem; height: 3.5rem;" role="status">
            <span class="visually-hidden">Cargando...</span>
        </div>
        <p class="mt-3 text-muted fw-light">Cargando tu información...</p>
    </div>

    <div id="contenidoPerfil" style="display:none;">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.3);">
                    <div class="card-body text-center p-5">
                        <!-- Avatar con glow -->
                        <div class="position-relative d-inline-block mb-4">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                 style="width:140px;height:140px;background: linear-gradient(135deg, #4a6cf7, #6a3de8);
                                        color:white;font-size:56px;font-weight:700;box-shadow: 0 0 0 4px rgba(74,108,247,0.2), 0 8px 30px rgba(74,108,247,0.35);
                                        border:4px solid white; transition: box-shadow 0.3s ease;"
                                 id="inicialUsuario">
                            </div>
                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2 border border-2 border-white"
                                  style="width:30px;height:30px;display:flex;align-items:center;justify-content:center; animation: pulse-dot 2s infinite;">
                                <i class="fas fa-check-circle text-white" style="font-size:16px;"></i>
                            </span>
                        </div>

                        <h4 class="fw-bold mb-1" id="perfilNombre" style="color: #1a2332;"></h4>
                        <p class="text-muted mb-3" id="perfilEmail"></p>
                        <span class="badge bg-primary bg-gradient px-4 py-2 rounded-pill fs-6 fw-normal" id="perfilRol"></span>

                        <hr class="my-4 opacity-50">

                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <button class="btn btn-primary btn-sm rounded-pill px-4" id="btnEditarPerfil" style="background: linear-gradient(135deg, #4a6cf7, #6a3de8); border: none;">
                                <i class="fas fa-pen me-1"></i> Editar
                            </button>
                            <button class="btn btn-outline-secondary btn-sm rounded-pill px-4" onclick="window.location.reload();" title="Refrescar datos">
                                <i class="fas fa-sync-alt me-1"></i> Refrescar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card h-100 border-0 shadow-lg rounded-4" style="background: rgba(255,255,255,0.85); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.3);">
                    <div class="card-body p-4 p-xl-5">
                        <h4 class="fw-bold mb-4" style="color: #1a2332;">
                            <i class="fas fa-id-card me-2" style="color: #4a6cf7;"></i>Información personal
                        </h4>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 d-flex align-items-center" style="background: #f8faff; border: 1px solid #e9edf5;">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:44px;height:44px;background: #eef2ff; color: #4a6cf7; flex-shrink:0;">
                                        <i class="fas fa-user-circle fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block fw-light">Nombre completo</small>
                                        <strong class="fs-6" id="infoNombre" style="color: #1a2332;"></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 d-flex align-items-center" style="background: #f8faff; border: 1px solid #e9edf5;">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:44px;height:44px;background: #eef2ff; color: #4a6cf7; flex-shrink:0;">
                                        <i class="fas fa-envelope fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block fw-light">Correo electrónico</small>
                                        <strong class="fs-6" id="infoEmail" style="color: #1a2332;"></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 d-flex align-items-center" style="background: #f8faff; border: 1px solid #e9edf5;">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:44px;height:44px;background: #eef2ff; color: #4a6cf7; flex-shrink:0;">
                                        <i class="fas fa-user-tag fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block fw-light">Tipo de cuenta</small>
                                        <strong class="fs-6" id="infoRol" style="color: #1a2332;"></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded-3 d-flex align-items-center" style="background: #f8faff; border: 1px solid #e9edf5;">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:44px;height:44px;background: #eef2ff; color: #4a6cf7; flex-shrink:0;">
                                        <i class="fas fa-calendar-alt fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block fw-light">Cuenta creada</small>
                                        <strong class="fs-6" id="infoFecha" style="color: #1a2332;"></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="button" class="btn btn-primary rounded-pill px-5" id="btnEditarPerfil2" style="display:none;">
                                <i class="fas fa-pen me-2"></i>Editar perfil
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-lg rounded-4 mt-4" id="formularioEdicion" style="display:none; background: rgba(255,255,255,0.92); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.3);">
            <div class="card-body p-4 p-xl-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0" style="color: #1a2332;">
                        <i class="fas fa-edit me-2" style="color: #4a6cf7;"></i>Editar información
                    </h4>
                    <button type="button" class="btn-close" id="btnCancelarEdicion" aria-label="Cerrar"></button>
                </div>

                <form id="formPerfil">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user me-1 text-primary"></i> Nombre
                            </label>
                            <input type="text" name="name" id="name"
                                   class="form-control form-control-lg rounded-3 border-0 shadow-sm"
                                   placeholder="Tu nombre completo" required
                                   style="background: #f0f4ff; transition: all 0.2s;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-envelope me-1 text-primary"></i> Correo electrónico
                            </label>
                            <input type="email" name="email" id="email"
                                   class="form-control form-control-lg rounded-3 border-0 shadow-sm"
                                   placeholder="tucorreo@ejemplo.com" required
                                   style="background: #f0f4ff; transition: all 0.2s;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-lock me-1 text-primary"></i> Nueva contraseña
                            </label>
                            <input type="password" name="password" id="password"
                                   class="form-control form-control-lg rounded-3 border-0 shadow-sm"
                                   placeholder="Dejar en blanco si no deseas cambiarla"
                                   style="background: #f0f4ff; transition: all 0.2s;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-check-circle me-1 text-primary"></i> Confirmar contraseña
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control form-control-lg rounded-3 border-0 shadow-sm"
                                   placeholder="Repite la nueva contraseña"
                                   style="background: #f0f4ff; transition: all 0.2s;">
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-3 flex-wrap">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm" id="btnGuardarPerfil" style="background: linear-gradient(135deg, #4a6cf7, #6a3de8); border: none;">
                            <i class="fas fa-save me-2"></i>Guardar cambios
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-lg rounded-pill px-5" id="btnCancelarEdicion2">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">


<script>
    $(document).ready(function(){
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        cargarPerfil();

        $('#btnEditarPerfil, #btnEditarPerfil2').click(function(){
            $('#formularioEdicion').slideDown('fast');
            $('#name').val($('#infoNombre').text().trim());
            $('#email').val($('#infoEmail').text().trim());
            $('#password').val('');
            $('#password_confirmation').val('');
            $('html, body').animate({
                scrollTop: $('#formularioEdicion').offset().top - 20
            }, 400);
        });

        $('#btnCancelarEdicion, #btnCancelarEdicion2').click(function(){
            $('#formularioEdicion').slideUp('fast');
            $('#password').val('');
            $('#password_confirmation').val('');
        });

        $('#formPerfil').submit(function(e){
            e.preventDefault();
            actualizarPerfil();
        });
    });

    function cargarPerfil(){
        $.ajax({
            url:"{{ route('perfil.data') }}",
            type:"GET",
            success:function(response){
                if(!response.success){
                    toastr.error('No fue posible cargar el perfil.');
                    return;
                }

                const usuario = response.usuario;
                const nombre = usuario.name || 'Usuario';
                const inicial = nombre.charAt(0).toUpperCase();

                // Avatar e información general
                $('#inicialUsuario').text(inicial);
                $('#perfilNombre').text(nombre);
                $('#perfilEmail').text(usuario.email);
                $('#perfilRol').text(formatearRol(usuario.role));

                // Datos en la columna derecha
                $('#infoNombre').text(nombre);
                $('#infoEmail').text(usuario.email);
                $('#infoRol').text(formatearRol(usuario.role));
                const fechaFormateada = usuario.created_at ? formatearFecha(usuario.created_at) : '—';
                $('#infoFecha').text(fechaFormateada);
                // También el badge del header
                $('#infoFechaBadge').text(fechaFormateada);

                // Ocultar loading y mostrar contenido
                $('#loadingPerfil').hide();
                $('#contenidoPerfil').fadeIn(400);
            },
            error:function(xhr){
                $('#loadingPerfil').hide();
                toastr.error('No fue posible cargar la información del perfil.');
                console.error(xhr);
            }
        });
    }

    function formatearRol(rol){
        const roles = {
            cliente: 'Cliente',
            veterinario: 'Veterinario',
            admin: 'Administrador'
        };
        return roles[rol] || rol;
    }

    function formatearFecha(fecha){
        const date = new Date(fecha);
        const dia = String(date.getDate()).padStart(2,'0');
        const mes = String(date.getMonth()+1).padStart(2,'0');
        const anio = date.getFullYear();
        return `${dia}/${mes}/${anio}`;
    }

    function actualizarPerfil(){
        const form = document.getElementById('formPerfil');
        const formData = new FormData(form);
        const boton = $('#btnGuardarPerfil');

        formData.append('_method','PUT');

        boton.prop('disabled', true);
        boton.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Guardando...');

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        
        $.ajax({
            url: "{{ route('perfil.update') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                toastr.options = { positionClass: "toast-bottom-right" };
                toastr.success(response.message || 'Perfil actualizado correctamente.');
                $('#formularioEdicion').slideUp('fast');
                $('#password').val('');
                $('#password_confirmation').val('');
                cargarPerfil();
            },
            error: function(xhr){
                let mensaje = 'No fue posible actualizar el perfil.';
                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                    mensaje = Object.values(xhr.responseJSON.errors).flat().join(' ');
                } else if (xhr.responseJSON?.message) {
                    mensaje = xhr.responseJSON.message;
                }
                toastr.options = { positionClass: "toast-bottom-right" };
                toastr.error(mensaje);
            },
            complete: function(){
                boton.prop('disabled', false);
                boton.html('<i class="fas fa-save me-2"></i>Guardar cambios');
            }
        });
    }
</script>
@endsection
