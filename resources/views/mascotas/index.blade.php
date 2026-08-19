@extends('layouts.my-dashboard-layout')

@section('content')
<link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .fila-mascota {
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .fila-mascota:hover {
        background-color: #f5f5f5;
    }

    /* Fila seleccionada: fondo para toda la fila y sus celdas */
    .fila-mascota.fila-seleccionada,
    .fila-mascota.fila-seleccionada td {
        background-color: #EBE3A7 !important;
    }

    /* Opcional: si quieres que al pasar el mouse sobre una fila seleccionada se mantenga el color */
    .fila-mascota.fila-seleccionada:hover,
    .fila-mascota.fila-seleccionada:hover td {
        background-color: #EBE3A7 !important;
    }
</style>
<div class="row">
    <div class="col-lg-7">
        <div class="card-style mb-30">
            <div class="title d-flex align-items-center justify-content-between mb-20" style="background: #EEEEEE; padding: 10px">
                <div>Mascotas</div>
                <button type="button" class="main-btn primary-btn btn-sm btn-hover py-1 px-2 text-xs" id="AddMascota">
                    <i class="lni lni-plus"></i> Agregar
                </button>
            </div>
            <div class="table-wrapper table-responsive">
                <table class="table" id="TableMascota">
                    <thead>
                        <tr>
                            <th><h6>#</h6></th>
                            <th><h6>Nombre</h6></th>
                            <th><h6>Especie</h6></th>
                            <th><h6>Raza</h6></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center">
                                Cargando mascotas...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="PaginacionMascotas" class="d-flex justify-content-center mt-3"></div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card-style mb-30" id="ResultadoDiv"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$(document).ready(function () {
    GetMascotas();
    EnEspera();
});

function GetMascotas() {
    $.ajax({
        url: "{{ route('mascotas.get') }}",
        type: "GET",
        success: function (response) {
            let tbody = $('#TableMascota tbody');
            tbody.empty();
            if (response.length === 0) {
                tbody.append('<tr><td colspan="4" class="text-center">No tienes mascotas registradas.</td></tr>');
                return;
            }
            $.each(response, function (index, mascota) {
                tbody.append(`
                    <tr class="fila-mascota" data-id="${mascota.id}" style="cursor: pointer;">
                        <td>${index + 1}</td>
                        <td>${mascota.nombre}</td>
                        <td>${mascota.especie}</td>
                        <td>${mascota.raza ?? 'Sin especificar'}</td>
                    </tr>
                `);
            });
        },
        error: function (xhr) {
            console.error(xhr);
            $('#TableMascota tbody').html('<tr><td colspan="4" class="text-center text-danger">Error al cargar las mascotas.</td></tr>');
        }
    });
}

$(document).on('click', '.fila-mascota', function () {
    $('.fila-mascota').removeClass('fila-seleccionada');
    $(this).addClass('fila-seleccionada');
    let mascotaId = $(this).data('id');
    GetMascota(mascotaId);
});

function GetMascota(id) {
    $('#ResultadoDiv').html(`
        <div class="d-flex flex-column align-items-center justify-content-center py-5">
            <div class="spinner-border text-primary mb-3" role="status"></div>
            <p class="text-muted">Cargando información...</p>
        </div>
    `);

    $.ajax({
        url: "{{ url('/get-mascota') }}/" + id,
        type: "GET",
        success: function (response) {
            let mascota = response.mascota;
            let fechaNac = mascota.fecha_nacimiento ? new Date(mascota.fecha_nacimiento) : null;
            let fechaFormateada = fechaNac ? fechaNac.toLocaleDateString('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' }) : 'Sin especificar';
            let edad = '';
            if (fechaNac) {
                let hoy = new Date();
                let años = hoy.getFullYear() - fechaNac.getFullYear();
                let mes = hoy.getMonth() - fechaNac.getMonth();
                if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) años--;
                edad = años > 0 ? `(${años} año${años > 1 ? 's' : ''})` : '(menor de 1 año)';
            }
            const especieColors = { 'Perro': 'primary', 'Gato': 'warning', 'Ave': 'success', 'Conejo': 'info', 'Otro': 'secondary' };
            let badgeEspecie = `<span class="badge bg-${especieColors[mascota.especie] || 'secondary'}">${mascota.especie}</span>`;
            let badgeSexo = '';
            if (mascota.sexo) {
                let sexoLower = mascota.sexo.toLowerCase();
                let icono = sexoLower === 'macho' ? '♂' : '♀';
                let color = sexoLower === 'macho' ? 'primary' : 'danger';
                badgeSexo = `<span class="badge bg-${color}">${icono} ${mascota.sexo}</span>`;
            } else {
                badgeSexo = `<span class="badge bg-secondary">Sin especificar</span>`;
            }
            let html = `
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between" style="border-bottom: 2px solid #f0f0f0;">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                <svg viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M106.0864 104.68352l7.54688 145.27488 75.2128 105.59488 157.95712-196.1984-114.25792-54.67136H106.0864z m794.09152-20.31616l9.09824 145.18784-62.65856 113.48992-179.33312-176.8704 107.264-67.35872 125.62944-14.44864z" fill="#5FCEFF"></path><path d="M851.36896 725.10464v101.88288c0 44.56448-36.12672 80.72192-80.72192 80.72192H250.38336c-44.56448 0-80.72192-36.15744-80.72192-80.72192v-109.07648c0 44.5952 36.15744 80.72192 80.72192 80.72192h520.26368c44.5952 0 80.72192-36.12672 80.72192-80.72192v7.1936z" fill="#FF4893"></path><path d="M637.24032 168.07936a340.90496 340.90496 0 0 1 114.28864 75.50976c61.70112 61.70112 99.84 146.89792 99.84 241.01376v233.30816c0 44.5952-36.12672 80.72192-80.72192 80.72192H250.38336c-44.56448 0-80.72192-36.12672-80.72192-80.72192V484.60288c0-188.23168 152.62208-340.85376 340.85376-340.85376 44.78976 0 87.56224 8.63232 126.72512 24.33024z m83.85536 169.72288a23.20896 23.20896 0 0 0-6.8096-16.46592 23.05024 23.05024 0 0 0-16.43008-6.8096 23.20896 23.20896 0 0 0-16.46592 6.8096 23.1936 23.1936 0 0 0-6.8096 16.46592c0 6.4256 2.59072 12.2112 6.8096 16.43008a23.33184 23.33184 0 0 0 16.46592 6.8096 23.23968 23.23968 0 0 0 23.23968-23.23968z m-363.52 0a23.20896 23.20896 0 0 0-6.8096-16.46592c-4.21888-4.21888-10.04032-6.8096-16.46592-6.8096s-12.24192 2.59072-16.43008 6.8096a23.1936 23.1936 0 0 0-6.8096 16.46592c0 6.4256 2.59072 12.2112 6.8096 16.43008a23.26528 23.26528 0 0 0 39.7056-16.43008z" fill="#FFB578"></path><path d="M714.28608 321.33632a23.1936 23.1936 0 0 1 6.8096 16.46592 23.23968 23.23968 0 0 1-23.23968 23.23968 23.33696 23.33696 0 0 1-16.46592-6.8096 23.12192 23.12192 0 0 1-6.8096-16.43008c0-6.4256 2.59072-12.24192 6.8096-16.46592a23.1936 23.1936 0 0 1 16.46592-6.8096c6.4256 0 12.24192 2.59072 16.43008 6.8096z m-363.52 0a23.2704 23.2704 0 0 1-32.896 32.896 23.12192 23.12192 0 0 1-6.8096-16.43008c0-6.4256 2.59072-12.24192 6.8096-16.46592a23.06048 23.06048 0 0 1 16.43008-6.8096 23.1936 23.1936 0 0 1 16.46592 6.8096z" fill="#8B87C1"></path><path d="M334.30528 383.42144a45.32224 45.32224 0 0 1-32.256-13.36832 45.32736 45.32736 0 0 1-13.3632-32.256c0-12.20096 4.74624-23.67488 13.3632-32.28672a45.1584 45.1584 0 0 1 32.256-13.3632 45.38368 45.38368 0 0 1 32.28672 13.35808 45.37344 45.37344 0 0 1 13.36832 32.29184c-0.00512 25.15968-20.48512 45.62432-45.65504 45.62432z" fill="#4F46A3"></path><path d="M334.30528 336.90624c-0.17408 0-0.37888 0.02048-0.54784 0.19456-0.2816 0.2816-0.31232 0.49664-0.31232 0.70144 0 0.18432 0.02048 0.36864 0.25088 0.60416a0.80896 0.80896 0 0 0 0.60928 0.26112 0.89088 0.89088 0 0 0 0.896-0.86528 0.83456 0.83456 0 0 0-0.26112-0.64512 0.82944 0.82944 0 0 0-0.63488-0.25088zM697.856 383.42144a45.5168 45.5168 0 0 1-32.2304-13.30176 45.3632 45.3632 0 0 1-13.42464-32.31744c0-12.20096 4.74624-23.67488 13.3632-32.28672a45.37856 45.37856 0 0 1 32.29184-13.3632c12.24192 0 23.71584 4.76672 32.3072 13.41952a45.29664 45.29664 0 0 1 13.30688 32.23552c0.00512 25.14944-20.45952 45.61408-45.61408 45.61408z" fill="#4F46A3"></path><path d="M697.856 336.90624a0.8192 0.8192 0 0 0-0.64512 0.26112 0.80896 0.80896 0 0 0-0.25088 0.64c0 0.18432 0.02048 0.36864 0.25088 0.60416 0.26112 0.26112 0.5376 0.26112 0.64512 0.26112a0.86528 0.86528 0 0 0 0.86528-0.86528 0.84992 0.84992 0 0 0-0.256-0.64512 0.77312 0.77312 0 0 0-0.60928-0.256z" fill="#4F46A3"></path><path d="M587.3664 532.1728v65.024c0 37.7856-30.72 68.5056-68.5056 68.5056-18.3296 0-35.4816-7.1168-48.4352-20.0704a68.12672 68.12672 0 0 1-20.0192-48.4352v-67.8912c17.4592-9.3184 33.0752-15.7696 46.08-30.5152v105.472c0 12.3392 10.0352 22.3744 22.3744 22.3744s22.3744-10.0352 22.3744-22.3744v-105.472c13.2608 13.6192 28.8256 24.9856 46.1312 33.3824z" fill="#FF4893"></path><path d="M658.4832 503.7056c12.3392 0 22.3744 10.0352 22.3744 22.3744 0 12.3904-10.0352 22.3744-22.3744 22.3744-8.96 0-17.7664-0.7168-26.368-2.1504v50.8928c0 62.464-50.7904 113.2544-113.2544 113.2544-30.2592 0-58.7264-11.776-80.0768-33.1776-21.4016-21.4016-33.1776-49.8176-33.1776-80.0768v-51.8656c-10.2912 2.048-20.992 3.1232-31.8976 3.1232-12.3904 0-22.3744-9.984-22.3744-22.3744 0-12.3392 9.984-22.3744 22.3744-22.3744 65.4336 0 118.7328-53.248 118.7328-118.7328a22.3232 22.3232 0 0 1 22.3232-22.3744c0.512 0 0.9216 0.1024 1.4336 0.1024 0.3584 0 0.768-0.1024 1.1776-0.1024 12.3392 0 22.3744 10.0352 22.3744 22.3744 0 65.4848 53.248 118.7328 118.7328 118.7328z m-71.1168 93.4912v-65.024a164.94592 164.94592 0 0 1-46.1312-33.3824v105.472c0 12.3392-10.0352 22.3744-22.3744 22.3744s-22.3744-10.0352-22.3744-22.3744v-105.472c-13.0048 14.7456-28.6208 21.1968-46.08 30.5152v67.8912c0 18.3296 7.1168 35.4816 20.0192 48.4352 12.9536 12.9536 30.1056 20.0704 48.4352 20.0704 37.7856 0 68.5056-30.72 68.5056-68.5056z" fill="#4F46A3"></path><path d="M926.49472 95.16544a22.36416 22.36416 0 0 0-17.10592-22.78912c-99.52768-24.59136-202.07104 1.50016-277.38112 69.8624a361.6256 361.6256 0 0 0-121.48736-20.86912 361.29792 361.29792 0 0 0-129.80224 23.99744C303.45216 78.86336 199.88992 55.55712 100.8128 83.1232c-0.3584 0.1024-0.67072 0.2816-1.01888 0.39936a22.41536 22.41536 0 0 0-10.57792 7.45472 22.4512 22.4512 0 0 0-3.92704 7.90016c-0.1024 0.36864-0.27136 0.70656-0.35328 1.08544-21.78048 100.74624 7.68512 202.97216 78.87872 276.22912a361.9072 361.9072 0 0 0-16.53248 108.416v342.38464c0 56.84736 46.24896 103.10144 103.10144 103.10144h520.26368c56.85248 0 103.10144-46.25408 103.10144-103.10144V484.608a363.8272 363.8272 0 0 0-18.47296-114.59584c68.05504-74.4704 94.54592-175.94368 71.2192-274.84672z m-42.0352 17.63328c15.4624 74.91072-2.2528 150.9632-48.36864 210.56512a363.63264 363.63264 0 0 0-68.72576-95.5904 361.3952 361.3952 0 0 0-89.51808-65.64352c58.55232-45.35808 133.00736-63.3344 206.61248-49.3312z m-758.31296 10.1888c73.63072-16.47104 149.08416-0.59904 209.23904 43.48416a365.77792 365.77792 0 0 0-153.28256 163.1232c-47.6928-57.48736-68.096-132.00384-55.95648-206.60736z m702.848 704.00512c0 32.16896-26.17344 58.3424-58.3424 58.3424H250.38336c-32.16896 0-58.3424-26.17344-58.3424-58.3424v-24.1664a102.4512 102.4512 0 0 0 58.3424 18.18624h520.26368a102.4512 102.4512 0 0 0 58.3424-18.18624v24.1664z m0-153.35936v44.27264c0 32.16896-26.17344 58.3424-58.3424 58.3424H250.38336c-32.16896 0-58.3424-26.17344-58.3424-58.3424V688.08192 484.60288c0-175.61088 142.86848-318.47424 318.47424-318.47424a316.88704 316.88704 0 0 1 116.93056 22.1696c0.8192 0.38912 1.65888 0.71168 2.52416 1.00352a316.6976 316.6976 0 0 1 105.73824 70.10816c60.16 60.15488 93.2864 140.12928 93.2864 225.18784v189.03552z" fill="#4F46A3"></path></g></svg>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">${mascota.nombre}</h5>
                                <div class="mt-1">${badgeEspecie} ${badgeSexo}</div>
                            </div>
                        </div>
                        <div>
                            
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start mb-3">
                                    <div>
                                        <small class="text-muted d-block">Fecha de nacimiento</small>
                                        <span class="fw-medium">${fechaFormateada} ${edad}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start mb-3">
                                    <div>
                                        <small class="text-muted d-block">Peso</small>
                                        <span class="fw-medium">${mascota.peso ? mascota.peso + ' Kg' : 'Sin especificar'}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <div>
                                        <small class="text-muted d-block">Color</small>
                                        <span class="fw-medium">${mascota.color ?? 'Sin especificar'}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start mb-3">
                                    <div>
                                        <small class="text-muted d-block">Raza</small>
                                        <span class="fw-medium">${mascota.raza ?? 'Sin especificar'}</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <div>
                                        <small class="text-muted d-block">Observaciones</small>
                                        <p class="mb-0 fw-medium" style="white-space: pre-wrap;">${mascota.observaciones ?? 'Sin observaciones'}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white text-muted small d-flex justify-content-between align-items-center">
                        <span><i class="lni lni-calendar-alt me-1"></i> Registrado: ${mascota.created_at ? new Date(mascota.created_at).toLocaleDateString('es-ES') : '—'}</span>
                        <span>ID: ${mascota.id}</span>
                    </div>
                </div>
            `;
            $('#ResultadoDiv').html(html);
        },
        error: function (xhr) {
            console.error(xhr);
            let mensaje = 'No se pudo cargar la información de la mascota.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                mensaje = xhr.responseJSON.message;
            }
            $('#ResultadoDiv').html(`
                <div class="text-center py-5 text-danger">
                    <i class="lni lni-warning" style="font-size: 45px;"></i>
                    <h5 class="mt-3">Error</h5>
                    <p>${mensaje}</p>
                </div>
            `);
        }
    });
}


$('#AddMascota').click(function () {
    $('#ResultadoDiv').html(`
        <div class="title mb-20" style="background: #EEEEEE; padding: 10px">
            <h4>Registrar mascota</h4>
        </div>
        <form id="FormMascota">
            <div class="row">
                <div class="col-md-6 mb-2" style="height: 70px;">
                    <div class="input-style-1">
                        <label>Nombre</label>
                        <input type="text" name="nombre" placeholder="Nombre de la mascota" style="height: 35px;" required>
                    </div>
                </div>
                <div class="col-md-6 mb-2" style="height: 70px;">
                    <div class="input-style-1">
                        <label>Especie</label>
                        <select name="especie" class="form-control" style="height: 35px;" required>
                            <option value="">Seleccionar especie</option>
                            <option value="Perro">Perro</option>
                            <option value="Gato">Gato</option>
                            <option value="Ave">Ave</option>
                            <option value="Conejo">Conejo</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-2" style="height: 70px;">
                    <div class="input-style-1">
                        <label>Raza</label>
                        <input type="text" name="raza" placeholder="Raza" style="height: 35px;">
                    </div>
                </div>
                <div class="col-md-6 mb-2" style="height: 70px;">
                    <div class="input-style-1">
                        <label>Sexo</label>
                        <select name="sexo" class="form-control" style="height: 35px;" required>
                            <option value="">Seleccionar sexo</option>
                            <option value="Macho">Macho</option>
                            <option value="Hembra">Hembra</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 mb-2" style="height: 70px;">
                    <div class="input-style-1">
                        <label>Fecha de nacimiento</label>
                        <input type="date" name="fecha_nacimiento" style="height: 35px;">
                    </div>
                </div>
                <div class="col-md-6 mb-2" style="height: 70px;">
                    <div class="input-style-1">
                        <label>Peso (Kg)</label>
                        <input type="number" name="peso" step="0.01" min="0" placeholder="Ej. 5.5" style="height: 35px;">
                    </div>
                </div>
                <div class="col-md-6 mb-2" style="height: 70px;">
                    <div class="input-style-1">
                        <label>Color</label>
                        <input type="text" name="color" placeholder="Color" style="height: 35px;">
                    </div>
                </div>
                <div class="col-md-12 mb-2">
                    <div class="input-style-1">
                        <label>Observaciones</label>
                        <textarea name="observaciones" rows="2" placeholder="Observaciones de la mascota"></textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="main-btn light-btn btn-sm btn-hover py-1 px-2 text-xs" id="CancelarMascota">Cancelar</button>
                        <button type="submit" class="main-btn primary-btn btn-sm btn-hover py-1 px-2 text-xs">
                            <i class="lni lni-save"></i> Guardar mascota
                        </button>
                    </div>
                </div>
            </div>
        </form>
    `);
});

$(document).on('click', '#CancelarMascota', function () {
    $('#ResultadoDiv').html(`
        <div class="text-center py-5">
            <h4>Selecciona una opción</h4>
            <p class="text-muted">Haz clic en "Agregar" para registrar una mascota.</p>
        </div>
    `);
});

$(document).on('submit', '#FormMascota', function (e) {
    e.preventDefault();
    let form = this;
    let formData = new FormData(form);
    let boton = $(form).find('button[type="submit"]');
    boton.prop('disabled', true);
    boton.html('<span class="spinner-border spinner-border-sm me-1"></span> Guardando...');

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.ajax({
        url: "{{ route('mascotas.store') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            EnEspera();
            GetMascotas();
            toastr.options = { positionClass: "toast-bottom-right" };
            toastr.success('Mascota registrada correctamente');
        },
        error: function (xhr) {
            console.error(xhr);
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let mensaje = '';
                $.each(errors, function (campo, errores) {
                    $.each(errores, function (index, error) {
                        mensaje += error + '\n';
                    });
                });
                alert(mensaje);
            } else {
                alert('Ocurrió un error al registrar la mascota.');
            }
        },
        complete: function () {
            boton.prop('disabled', false);
            boton.html('<i class="lni lni-save"></i> Guardar mascota');
        }
    });
});

function EnEspera() {
    $('#ResultadoDiv').empty().html(`
        <div class="d-flex flex-column align-items-center justify-content-center text-center py-5">
            <svg fill="#EAEFEF" width="250px" height="250px" viewBox="-8.48 -8.48 32.96 32.96" id="wait-16px" xmlns="http://www.w3.org/2000/svg" stroke="#d4d4d4">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                    <path id="Path_14" data-name="Path 14" d="M-13.5,3.5V8a.5.5,0,0,1-.5.5.5.5,0,0,1-.5-.5V3.5A.5.5,0,0,1-14,3,.5.5,0,0,1-13.5,3.5ZM-13,.55a.5.5,0,0,0-.465-.532Q-13.731,0-14,0a8.009,8.009,0,0,0-8,8,8.009,8.009,0,0,0,8,8q.268,0,.534-.018A.5.5,0,0,0-13,15.45a.507.507,0,0,0-.533-.466c-.154.011-.309.016-.466.016a7.008,7.008,0,0,1-7-7,7.008,7.008,0,0,1,7-7c.157,0,.312,0,.466.016h.034A.5.5,0,0,0-13,.55Zm2.126,13.716a7.165,7.165,0,0,1-.842.354.5.5,0,0,0-.31.635.5.5,0,0,0,.473.337.516.516,0,0,0,.163-.027,8.087,8.087,0,0,0,.962-.4.5.5,0,0,0,.224-.671A.5.5,0,0,0-10.875,14.266ZM-8.738,3.383a.5.5,0,0,0,.376.171.5.5,0,0,0,.33-.124.5.5,0,0,0,.046-.706,7.93,7.93,0,0,0-.739-.739.5.5,0,0,0-.7.047.5.5,0,0,0,.046.7A6.91,6.91,0,0,1-8.738,3.383Zm-3.005-2.011a6.892,6.892,0,0,1,.845.351.5.5,0,0,0,.221.051.5.5,0,0,0,.448-.278.5.5,0,0,0-.227-.67,8.041,8.041,0,0,0-.964-.4.5.5,0,0,0-.635.312A.5.5,0,0,0-11.743,1.372ZM-6.73,9.919a.5.5,0,0,0-.633.314,7.106,7.106,0,0,1-.348.845.5.5,0,0,0,.229.67.5.5,0,0,0,.219.05.5.5,0,0,0,.45-.279,8.145,8.145,0,0,0,.4-.967A.5.5,0,0,0-6.73,9.919ZM-8.721,12.6a7.043,7.043,0,0,1-.644.649.5.5,0,0,0-.042.706.5.5,0,0,0,.374.168.493.493,0,0,0,.331-.126,7.9,7.9,0,0,0,.735-.74.5.5,0,0,0-.048-.706A.5.5,0,0,0-8.721,12.6Zm2.7-5.135A.5.5,0,0,0-6.551,7a.5.5,0,0,0-.465.532C-7.005,7.685-7,7.842-7,8s0,.3-.014.442a.5.5,0,0,0,.466.532h.033a.5.5,0,0,0,.5-.467C-6.005,8.34-6,8.17-6,8S-6.006,7.639-6.018,7.462Zm-1.354-1.72a.5.5,0,0,0,.474.339.508.508,0,0,0,.161-.027.5.5,0,0,0,.312-.635,8.056,8.056,0,0,0-.4-.964.5.5,0,0,0-.67-.226.5.5,0,0,0-.226.669A6.939,6.939,0,0,1-7.372,5.742Z" transform="translate(22)"></path>
                </g>
            </svg>
            <h4 class="mb-2">Esperando una selección</h4>
            <p class="text-muted mb-0">Selecciona una mascota o pulsa <strong>Agregar</strong> para registrar una nueva.</p>
        </div>
    `);
}
</script>
@endsection

@section('scripts')
@endsection