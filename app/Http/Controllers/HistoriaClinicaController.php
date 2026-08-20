<?php

namespace App\Http\Controllers;

use App\Models\Mascota;

class HistoriaClinicaController extends Controller
{
    public function index()
    {
        return view('historia-clinica');
    }

    public function mascotas()
    {
        $mascotas=Mascota::where('user_id',auth()->id())
            ->orderBy('nombre')
            ->get([
                'id',
                'nombre',
                'especie',
                'raza'
            ]);

        return response()->json([
            'success'=>true,
            'mascotas'=>$mascotas
        ]);
    }

    public function consultas($mascotaId)
    {
        $mascota=Mascota::where('id',$mascotaId)
            ->where('user_id',auth()->id())
            ->firstOrFail();

        $consultas=$mascota->consultas()
            ->with('veterinario:id,name')
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'success'=>true,
            'consultas'=>$consultas
        ]);
    }

    public function vacunas($mascotaId)
    {
        $mascota=Mascota::where('id',$mascotaId)
            ->where('user_id',auth()->id())
            ->firstOrFail();

        $vacunas=$mascota->vacunas()
            ->with('veterinario:id,name')
            ->orderByDesc('fecha_aplicacion')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'success'=>true,
            'vacunas'=>$vacunas
        ]);
    }

    public function desparasitaciones($mascotaId)
    {
        $mascota=Mascota::where('id',$mascotaId)
            ->where('user_id',auth()->id())
            ->firstOrFail();

        $desparasitaciones=$mascota->desparasitaciones()
            ->with('veterinario:id,name')
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'success'=>true,
            'desparasitaciones'=>$desparasitaciones
        ]);
    }

    public function tratamientos($mascotaId)
    {
        $mascota=Mascota::where('id',$mascotaId)
            ->where('user_id',auth()->id())
            ->firstOrFail();

        $tratamientos=$mascota->tratamientos()
            ->with([
                'veterinario:id,name',
                'consulta:id,fecha,motivo'
            ])
            ->orderByDesc('fecha_inicio')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'success'=>true,
            'tratamientos'=>$tratamientos
        ]);
    }
}