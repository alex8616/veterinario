@extends('layouts.my-dashboard-layout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.lineicons.com/5.0/lineicons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="row">
    <div class="col-lg-7">
        <div class="card-style mb-30">

            {{-- Encabezado --}}
            <div class="title d-flex align-items-center justify-content-between mb-20"
                 style="background: #EEEEEE; padding: 10px">

                <div>
                    Mascotas
                </div>

                <button type="button"
                        class="main-btn primary-btn btn-sm btn-hover py-1 px-2 text-xs"
                        id="AddMascota">
                    <i class="lni lni-plus"></i> Agregar
                </button>
            </div>

            {{-- Tabla --}}
            <div class="table-wrapper table-responsive">
                <table class="table" id="TableMascota">

                    <thead>
                        <tr>
                            <th><h6>#</h6></th>
                            <th><h6>Nombre</h6></th>
                            <th><h6>Especie</h6></th>
                            <th><h6>Raza</h6></th>
                            <th><h6>Acciones</h6></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td colspan="5" class="text-center">
                                Cargando mascotas...
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>

        </div>
    </div>

    <div class="col-lg-5">
        <div class="card-style mb-30" id="ResultadoDiv">
        </div>
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

function EnEspera(){

    $('#ResultadoDiv').html(`
        <div class="d-flex flex-column align-items-center justify-content-center text-center py-5">
            <svg fill="#EAEFEF" width="250px" height="250px" viewBox="-8.48 -8.48 32.96 32.96" id="wait-16px" xmlns="http://www.w3.org/2000/svg" stroke="#d4d4d4"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path id="Path_14" data-name="Path 14" d="M-13.5,3.5V8a.5.5,0,0,1-.5.5.5.5,0,0,1-.5-.5V3.5A.5.5,0,0,1-14,3,.5.5,0,0,1-13.5,3.5ZM-13,.55a.5.5,0,0,0-.465-.532Q-13.731,0-14,0a8.009,8.009,0,0,0-8,8,8.009,8.009,0,0,0,8,8q.268,0,.534-.018A.5.5,0,0,0-13,15.45a.507.507,0,0,0-.533-.466c-.154.011-.309.016-.466.016a7.008,7.008,0,0,1-7-7,7.008,7.008,0,0,1,7-7c.157,0,.312,0,.466.016h.034A.5.5,0,0,0-13,.55Zm2.126,13.716a7.165,7.165,0,0,1-.842.354.5.5,0,0,0-.31.635.5.5,0,0,0,.473.337.516.516,0,0,0,.163-.027,8.087,8.087,0,0,0,.962-.4.5.5,0,0,0,.224-.671A.5.5,0,0,0-10.875,14.266ZM-8.738,3.383a.5.5,0,0,0,.376.171.5.5,0,0,0,.33-.124.5.5,0,0,0,.046-.706,7.93,7.93,0,0,0-.739-.739.5.5,0,0,0-.7.047.5.5,0,0,0,.046.7A6.91,6.91,0,0,1-8.738,3.383Zm-3.005-2.011a6.892,6.892,0,0,1,.845.351.5.5,0,0,0,.221.051.5.5,0,0,0,.448-.278.5.5,0,0,0-.227-.67,8.041,8.041,0,0,0-.964-.4.5.5,0,0,0-.635.312A.5.5,0,0,0-11.743,1.372ZM-6.73,9.919a.5.5,0,0,0-.633.314,7.106,7.106,0,0,1-.348.845.5.5,0,0,0,.229.67.5.5,0,0,0,.219.05.5.5,0,0,0,.45-.279,8.145,8.145,0,0,0,.4-.967A.5.5,0,0,0-6.73,9.919ZM-8.721,12.6a7.043,7.043,0,0,1-.644.649.5.5,0,0,0-.042.706.5.5,0,0,0,.374.168.493.493,0,0,0,.331-.126,7.9,7.9,0,0,0,.735-.74.5.5,0,0,0-.048-.706A.5.5,0,0,0-8.721,12.6Zm2.7-5.135A.5.5,0,0,0-6.551,7a.5.5,0,0,0-.465.532C-7.005,7.685-7,7.842-7,8s0,.3-.014.442a.5.5,0,0,0,.466.532h.033a.5.5,0,0,0,.5-.467C-6.005,8.34-6,8.17-6,8S-6.006,7.639-6.018,7.462Zm-1.354-1.72a.5.5,0,0,0,.474.339.508.508,0,0,0,.161-.027.5.5,0,0,0,.312-.635,8.056,8.056,0,0,0-.4-.964.5.5,0,0,0-.67-.226.5.5,0,0,0-.226.669A6.939,6.939,0,0,1-7.372,5.742Z" transform="translate(22)"></path> </g></svg>
            <h4 class="mb-2">
                Esperando una selección
            </h4>

            <p class="text-muted mb-0">
                Selecciona una mascota o pulsa <strong>Agregar</strong>
                para registrar una nueva.
            </p>

        </div>
    `);
}

function GetMascotas() {

    $.ajax({
        url: "{{ route('mascotas.get') }}",
        type: "GET",

        success: function (response) {

            let tbody = $('#TableMascota tbody');

            tbody.empty();

            if (response.length === 0) {

                tbody.append(`
                    <tr>
                        <td colspan="5" class="text-center">
                            No tienes mascotas registradas.
                        </td>
                    </tr>
                `);

                return;
            }

            $.each(response, function (index, mascota) {

                tbody.append(`
                    <tr>
                        <td>
                            ${index + 1}
                        </td>

                        <td>
                            ${mascota.nombre}
                        </td>

                        <td>
                            ${mascota.especie}
                        </td>

                        <td>
                            ${mascota.raza ?? 'Sin especificar'}
                        </td>

                        <td>

                            <button type="button"
                                    class="main-btn primary-btn btn-sm btn-hover py-1 px-2"
                                    onclick="EditarMascota(${mascota.id})">
                                <i class="lni lni-pencil"></i>
                            </button>

                            <button type="button"
                                    class="main-btn danger-btn btn-sm btn-hover py-1 px-2"
                                    onclick="EliminarMascota(${mascota.id})">
                                <i class="lni lni-trash-can"></i>
                            </button>

                        </td>
                    </tr>
                `);

            });

        },

        error: function (xhr) {

            console.error(xhr);

            $('#TableMascota tbody').html(`
                <tr>
                    <td colspan="5" class="text-center text-danger">
                        Error al cargar las mascotas.
                    </td>
                </tr>
            `);

        }
    });

}

function EditarMascota(id) {

    console.log('Editar mascota:', id);

}

function EliminarMascota(id) {

    console.log('Eliminar mascota:', id);

}

$('#AddMascota').click(function () {

    $('#ResultadoDiv').html(`

        <div class="title mb-20">
            <h4>Registrar mascota</h4>
        </div>

        <form id="FormMascota">

            <div class="row">

                <!-- Nombre -->
                <div class="col-md-6">
                    <div class="input-style-1">
                        <label>Nombre</label>
                        <input type="text"
                               name="nombre"
                               placeholder="Nombre de la mascota"
                               required>
                    </div>
                </div>

                <!-- Especie -->
                <div class="col-md-6">
                    <div class="input-style-1">
                        <label>Especie</label>
                        <select name="especie" class="form-control" required>
                            <option value="">Seleccionar especie</option>
                            <option value="Perro">Perro</option>
                            <option value="Gato">Gato</option>
                            <option value="Ave">Ave</option>
                            <option value="Conejo">Conejo</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>

                <!-- Raza -->
                <div class="col-md-6">
                    <div class="input-style-1">
                        <label>Raza</label>
                        <input type="text"
                               name="raza"
                               placeholder="Raza">
                    </div>
                </div>

                <!-- Sexo -->
                <div class="col-md-6">
                    <div class="input-style-1">
                        <label>Sexo</label>
                        <select name="sexo" class="form-control" required>
                            <option value="">Seleccionar sexo</option>
                            <option value="Macho">Macho</option>
                            <option value="Hembra">Hembra</option>
                        </select>
                    </div>
                </div>

                <!-- Fecha de nacimiento -->
                <div class="col-md-6">
                    <div class="input-style-1">
                        <label>Fecha de nacimiento</label>
                        <input type="date"
                               name="fecha_nacimiento">
                    </div>
                </div>

                <!-- Peso -->
                <div class="col-md-6">
                    <div class="input-style-1">
                        <label>Peso (Kg)</label>
                        <input type="number"
                               name="peso"
                               step="0.01"
                               min="0"
                               placeholder="Ej. 5.5">
                    </div>
                </div>

                <!-- Color -->
                <div class="col-md-6">
                    <div class="input-style-1">
                        <label>Color</label>
                        <input type="text"
                               name="color"
                               placeholder="Color">
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="col-md-12">
                    <div class="input-style-1">
                        <label>Observaciones</label>
                        <textarea name="observaciones"
                                  rows="4"
                                  placeholder="Observaciones de la mascota"></textarea>
                    </div>
                </div>

                <!-- Botones -->
                <div class="col-md-12">
                    <div class="d-flex justify-content-end gap-2">

                        <button type="button"
                                class="main-btn light-btn btn-sm btn-hover py-1 px-2 text-xs"
                                id="CancelarMascota">
                            Cancelar
                        </button>

                        <button type="submit"
                            class="main-btn primary-btn btn-sm btn-hover py-1 px-2 text-xs">
                            <i class="lni lni-save"></i>
                            Guardar mascota
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
            <p class="text-muted">
                Haz clic en "Agregar" para registrar una mascota.
            </p>
        </div>
    `);

});

$(document).on('submit', '#FormMascota', function (e) {

    e.preventDefault();

    let form = this;
    let formData = new FormData(form);

    let boton = $(form).find('button[type="submit"]');

    boton.prop('disabled', true);

    boton.html(`
        <span class="spinner-border spinner-border-sm me-1"></span>
        Guardando...
    `);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: "{{ route('mascotas.store') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,

        success: function (response) {
            toastr.options = {
                positionClass: "toast-bottom-right"
            };
            toastr.success('Mascota registrada correctamente');
            form.reset();
            GetMascotas();
            EnEspera();
            $('#AddMascota').trigger('click');
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

            boton.html(`
                <i class="lni lni-save"></i>
                Guardar mascota
            `);

        }
    });

});
</script>
@endsection


@section('scripts')

@endsection