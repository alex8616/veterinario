@extends('layouts.my-dashboard-layout')

@section('content')
<link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* Estilo para el botón de hora seleccionado (verde) */
    .hora-cita.active {
        background-color: #28a745 !important;
        color: white !important;
        border-color: #28a745 !important;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card-style mb-30">
            <div class="title d-flex align-items-center justify-content-between mb-20">
                <div>
                    <h6 class="mb-5">Mis Citas</h6>
                    <p class="text-sm text-gray mb-0">Consulta tus próximas citas veterinarias</p>
                </div>
                <button type="button" class="main-btn primary-btn btn-sm btn-hover" id="btnNuevaCita">
                    <i class="lni lni-plus"></i> Nueva cita
                </button>
            </div>

            <div id="mensajeCitas"></div>

            <div id="loadingCitas" class="text-center py-5">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2 text-sm text-gray">Cargando citas...</p>
            </div>

            <div class="table-wrapper table-responsive" style="display:none;" id="contenedorTablaCitas">
                <table class="table" id="tablaCitas">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mascota</th>
                            <th>Fecha</th>
                            <th>Hora</th>
                            <th>Motivo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="listaCitas"></tbody>
                </table>
            </div>

            <div id="sinCitas" class="text-center py-5" style="display:none;">
                <h6>No tienes citas registradas</h6>
                <p class="text-sm text-gray">Cuando tengas una cita aparecerá aquí.</p>
            </div>
        </div>
    </div>
</div>

{{-- Modal Nueva Cita --}}
<div class="modal fade" id="modalNuevaCita" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formNuevaCita">
                <div class="modal-body">
                    <div id="mensajeNuevaCita"></div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Mascota</label>
                                <select name="mascota_id" id="mascota_id" class="form-control" required>
                                    <option value="">Seleccione una mascota</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Veterinario</label>
                                <select name="veterinario_id" id="veterinario_id" class="form-control" required>
                                    <option value="">Seleccione un veterinario</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Motivo de la cita</label>
                                <input type="text" name="motivo" id="motivo" class="form-control" placeholder="Ej. Consulta general" maxlength="255" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Observaciones</label>
                                <textarea name="observaciones" id="observaciones" class="form-control" rows="3" placeholder="Información adicional..."></textarea>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Fecha</label>
                                <input type="date" name="fecha" id="fecha" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Turno</label>
                                <select name="turno" id="turno" class="form-control" required>
                                    <option value="">Seleccione un turno</option>
                                    <option value="mañana"> Mañana</option>
                                    <option value="tarde"> Tarde</option>
                                    <option value="noche"> Noche</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hora de la cita</label>
                                <input type="hidden" name="hora" id="hora" required>
                                <div id="horariosContainer" class="d-flex flex-wrap gap-2 mt-2">
                                    <div class="text-muted text-sm">Seleccione fecha, veterinario y turno.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="main-btn light-btn btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="main-btn primary-btn btn-sm" id="btnGuardarCita">Guardar cita</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    $(document).ready(function() {
        cargarCitas();

        $('#btnNuevaCita').click(function() {
            cargarMascotas();
            cargarVeterinarios();
            $('#modalNuevaCita').modal('show');
            $('#horariosContainer').html('<div class="text-muted text-sm">Seleccione fecha, veterinario y turno.</div>');
            $('#hora').val('');
        });

        $('#formNuevaCita').submit(function(e) {
            e.preventDefault();
            guardarCita();
        });

        $('#veterinario_id, #fecha, #turno').on('change', function() {
            cargarHorariosDisponibles();
        });
    });

    function cargarCitas() {
        $('#loadingCitas').show();
        $('#contenedorTablaCitas').hide();
        $('#sinCitas').hide();
        $.ajax({
            url: "{{ route('citas.data') }}",
            type: "GET",
            success: function(response) {
                console.log("Cargo datos");
                console.log(response);
                $('#loadingCitas').hide();
                let tbody = $('#listaCitas');
                tbody.empty();
                if (!response.success) {
                    $('#mensajeCitas').html(`<div class="alert alert-danger">No fue posible cargar las citas.</div>`);
                    return;
                }
                const citas = response.citas;
                if (citas.length === 0) {
                    $('#sinCitas').show();
                    return;
                }
                $('#contenedorTablaCitas').show();
                $.each(citas, function(index, cita) {
                    let estadoBadge = '';
                    switch (cita.estado) {
                        case 'pendiente':
                            estadoBadge = `<span class="badge bg-warning">Pendiente</span>`;
                            break;
                        case 'confirmada':
                            estadoBadge = `<span class="badge bg-success">Confirmada</span>`;
                            break;
                        case 'cancelada':
                            estadoBadge = `<span class="badge bg-danger">Cancelada</span>`;
                            break;
                        case 'atendida':
                            estadoBadge = `<span class="badge bg-info">Atendida</span>`;
                            break;
                        default:
                            estadoBadge = `<span class="badge bg-secondary">${cita.estado}</span>`;
                    }
                    tbody.append(`
                        <tr>
                            <td>${index + 1}</td>
                            <td>${cita.mascota ? cita.mascota.nombre : '—'}</td>
                            <td>${formatearFecha(cita.fecha)}</td>
                            <td>${formatearHora(cita.hora)}</td>
                            <td>${cita.motivo}</td>
                            <td>${estadoBadge}</td>
                        </tr>
                    `);
                });
            },
            error: function(xhr) {
                $('#loadingCitas').hide();
                console.error(xhr);
                $('#mensajeCitas').html(`<div class="alert alert-danger">Error al cargar las citas. Intente nuevamente.</div>`);
            }
        });
    }

    function formatearFecha(fecha){
        const date = new Date(fecha);
        const dia = String(date.getDate()).padStart(2, '0');
        const mes = String(date.getMonth() + 1).padStart(2, '0');
        const anio = date.getFullYear();
        return `${dia}/${mes}/${anio}`;
    }

    function formatearHora(hora){
        return hora.substring(0, 5);
    }

    async function cargarHorariosDisponibles() {
        const veterinarioId = document.getElementById('veterinario_id').value;
        const fecha = document.getElementById('fecha').value;
        const turno = document.getElementById('turno').value;
        const container = document.getElementById('horariosContainer');
        const inputHora = document.getElementById('hora');

        inputHora.value = '';

        if (!veterinarioId || !fecha || !turno) {
            container.innerHTML = `<div class="text-muted text-sm">Seleccione fecha, veterinario y turno.</div>`;
            return;
        }

        container.innerHTML = `<div class="text-muted text-sm">Consultando horarios...</div>`;

        try {
            const url = `{{ route('citas.horariosDisponibles') }}?veterinario_id=${veterinarioId}&fecha=${fecha}&turno=${encodeURIComponent(turno)}`;
            const response = await fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();

            if (!data.success) {
                container.innerHTML = `<div class="alert alert-danger">No fue posible consultar los horarios.</div>`;
                return;
            }
            mostrarHorarios(data.horarios);
        } catch (error) {
            console.error(error);
            container.innerHTML = `<div class="alert alert-danger">Ocurrió un error al consultar los horarios.</div>`;
        }
    }

    function mostrarHorarios(horarios) {
        const container = document.getElementById('horariosContainer');
        const inputHora = document.getElementById('hora');
        inputHora.value = '';
        container.innerHTML = '';

        if (!horarios || horarios.length === 0) {
            container.innerHTML = `<div class="text-muted text-sm">No hay horarios disponibles para esta combinación.</div>`;
            return;
        }

        horarios.forEach(function(item) {
            const boton = document.createElement('button');
            boton.type = 'button';
            boton.textContent = item.hora;
            boton.classList.add('btn', 'btn-sm', 'hora-cita');

            if (item.disponible) {
                boton.classList.add('btn-outline-secondary');
                boton.addEventListener('click', function() {
                    // Quitar selección anterior
                    document.querySelectorAll('.hora-cita').forEach(function(el) {
                        el.classList.remove('active', 'btn-success');
                        el.classList.add('btn-outline-secondary');
                    });
                    // Marcar como seleccionado (verde)
                    boton.classList.remove('btn-outline-secondary');
                    boton.classList.add('active', 'btn-success');
                    inputHora.value = item.hora;
                });
            } else {
                boton.classList.add('btn-secondary');
                boton.disabled = true;
                boton.title = 'Horario ocupado';
            }

            container.appendChild(boton);
        });
    }

    function guardarCita() {
        let form = document.getElementById('formNuevaCita');
        let formData = new FormData(form);
        let boton = document.getElementById('btnGuardarCita');

        if (!$('#hora').val()) {
            toastr.error('Debe seleccionar una hora disponible.');
            return;
        }

        boton.disabled = true;
        boton.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span>Guardando...`;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('citas.store') }}",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                toastr.options = { positionClass: "toast-bottom-right" };
                toastr.success(response.message);
                $('#modalNuevaCita').modal('hide');
                form.reset();
                $('#hora').val('');
                $('#horariosContainer').html(`
                    <div class="text-muted text-sm">
                        Seleccione fecha, veterinario y turno.
                    </div>
                `);
                cargarCitas();
            },
            error: function(xhr) {
                console.error(xhr);
                let mensaje = 'Ocurrió un error al guardar la cita.';
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    let erroresTexto = '';
                    $.each(xhr.responseJSON.errors, function(campo, errores) {
                        erroresTexto += errores.join(' ') + ' ';
                    });
                    mensaje = erroresTexto;
                } else if (xhr.status === 409 && xhr.responseJSON && xhr.responseJSON.message) {
                    mensaje = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    mensaje = xhr.responseJSON.message;
                }
                toastr.error(mensaje);
                if (xhr.status === 409) {
                    cargarHorariosDisponibles();
                }
            },
            complete: function() {
                boton.disabled = false;
                boton.innerHTML = 'Guardar cita';
            }
        });
    }

    async function cargarMascotas() {
        const select = document.getElementById('mascota_id');
        select.innerHTML = '<option value="">Cargando mascotas...</option>';
        try {
            const response = await fetch('{{ route('citas.mascotas') }}', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            select.innerHTML = '<option value="">Seleccione una mascota</option>';
            if (data.success && data.mascotas) {
                data.mascotas.forEach(function(mascota) {
                    select.innerHTML += `<option value="${mascota.id}">${mascota.nombre}</option>`;
                });
            }
        } catch (error) {
            console.error(error);
            select.innerHTML = '<option value="">Error al cargar mascotas</option>';
        }
    }

    async function cargarVeterinarios() {
        const select = document.getElementById('veterinario_id');
        select.innerHTML = '<option value="">Cargando veterinarios...</option>';
        try {
            const response = await fetch('{{ route('citas.veterinarios') }}', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            select.innerHTML = '<option value="">Seleccione un veterinario</option>';
            if (data.success && data.veterinarios) {
                data.veterinarios.forEach(function(veterinario) {
                    select.innerHTML += `<option value="${veterinario.id}">${veterinario.name}</option>`;
                });
            }
        } catch (error) {
            console.error(error);
            select.innerHTML = '<option value="">Error al cargar veterinarios</option>';
        }
    }
</script>
@endsection