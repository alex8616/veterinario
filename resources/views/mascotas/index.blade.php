@extends('layouts.my-dashboard-layout')

@section('content')

<div class="row">
    <div class="col-lg-7">
        <div class="card-style mb-30">
            {{-- Encabezado --}}
            <div class="title d-flex align-items-center justify-content-between mb-20" style="background: #EEEEEE">
                <div>
                    <h6 class="mb-5">Mascotas</h6>
                </div>

                <button
                    type="button"
                    class="main-btn primary-btn btn-sm btn-hover">
                    <i class="lni lni-plus"></i>
                    Agregar
                </button>
            </div>

            {{-- Tabla --}}
            <div class="table-wrapper table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <h6>#</h6>
                            </th>
                            <th>
                                <h6>Nombre</h6>
                            </th>
                            <th>
                                <h6>Especie</h6>
                            </th>
                            <th>
                                <h6>Raza</h6>
                            </th>
                            <th>
                                <h6>Acciones</h6>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- Aquí posteriormente cargaremos las mascotas con AJAX --}}
                        <tr>
                            <td>1</td>
                            <td>Max</td>
                            <td>Perro</td>
                            <td>Labrador</td>
                            <td>
                                <button class="main-btn light-btn btn-sm">
                                    Ver
                                </button>

                                <button class="main-btn warning-btn btn-sm">
                                    Editar
                                </button>

                                <button class="main-btn danger-btn btn-sm">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <div class="col-lg-5">
        <h1>Holas</h1>        
    </div>
</div>

@endsection