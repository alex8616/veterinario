<?php

namespace App\Http\Controllers;

use App\Models\Mascota;
use Illuminate\Http\Request;

class MascotaController extends Controller
{
    /**
     * Mostrar las mascotas del usuario autenticado.
     */
    public function index()
    {
        $mascotas = Mascota::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('mascotas.index', compact('mascotas'));
    }

    /**
     * Mostrar formulario para registrar una mascota.
     */
    public function create()
    {
        return view('mascotas.create');
    }

    /**
     * Guardar una nueva mascota.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'especie' => 'required|string|max:50',
            'raza' => 'nullable|string|max:100',
            'sexo' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'peso' => 'nullable|numeric|min:0|max:999.99',
            'color' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $datos['user_id'] = auth()->id();

        Mascota::create($datos);

        return redirect()
            ->route('mascotas.index')
            ->with('success', 'Mascota registrada correctamente.');
    }

    /**
     * Mostrar una mascota.
     */
    public function show(Mascota $mascota)
    {
        $this->verificarPropietario($mascota);

        return view('mascotas.show', compact('mascota'));
    }

    /**
     * Mostrar formulario para editar una mascota.
     */
    public function edit(Mascota $mascota)
    {
        $this->verificarPropietario($mascota);

        return view('mascotas.edit', compact('mascota'));
    }

    /**
     * Actualizar una mascota.
     */
    public function update(Request $request, Mascota $mascota)
    {
        $this->verificarPropietario($mascota);

        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'especie' => 'required|string|max:50',
            'raza' => 'nullable|string|max:100',
            'sexo' => 'nullable|string|max:20',
            'fecha_nacimiento' => 'nullable|date',
            'peso' => 'nullable|numeric|min:0|max:999.99',
            'color' => 'nullable|string|max:50',
            'observaciones' => 'nullable|string',
        ]);

        $mascota->update($datos);

        return redirect()
            ->route('mascotas.index')
            ->with('success', 'Mascota actualizada correctamente.');
    }

    /**
     * Eliminar una mascota.
     */
    public function destroy(Mascota $mascota)
    {
        $this->verificarPropietario($mascota);

        $mascota->delete();

        return redirect()
            ->route('mascotas.index')
            ->with('success', 'Mascota eliminada correctamente.');
    }

    /**
     * Verificar que la mascota pertenece al usuario autenticado.
     */
    private function verificarPropietario(Mascota $mascota)
    {
        abort_unless(
            $mascota->user_id === auth()->id(),
            403
        );
    }
}