<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;

class MascotaController extends Controller
{
    public function index()
    {
        $mascotas = Mascota::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('mascotas.index', compact('mascotas'));
    }

    public function GetMascotas()
    {
        $clienteId = auth()->user()->id;

        $mascotas = Mascota::where('user_id', $clienteId)
        ->orderBy('id', 'desc')
        ->get();

        return response()->json($mascotas);
    }

    public function CrearMascota(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string|max:100',
            'raza' => 'nullable|string|max:100',
            'sexo' => 'required|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'peso' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string',
        ]);

        $mascota = Mascota::create([
            'user_id' => auth()->id(),
            'nombre' => $validated['nombre'],
            'especie' => $validated['especie'],
            'raza' => $validated['raza'] ?? null,
            'sexo' => $validated['sexo'],
            'fecha_nacimiento' => $validated['fecha_nacimiento'] ?? null,
            'peso' => $validated['peso'] ?? null,
            'color' => $validated['color'] ?? null,
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mascota registrada correctamente.',
            'mascota' => $mascota
        ], 201);
    }
}