<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;

class CitaController extends Controller
{
    public function index()
    {
        $citas = Cita::with('mascota')
            ->whereHas('mascota', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        return response()->json([
            'success' => true,
            'citas' => $citas
        ]);
    }

    public function indexPage()
    {
        return view('citas.index');
    }

    public function mascotas()
    {
        $mascotas = \App\Models\Mascota::where('user_id', auth()->id())
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'success' => true,
            'mascotas' => $mascotas
        ]);
    }

    public function veterinarios()
    {
        $veterinarios = \App\Models\User::where('role', 'veterinario')
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return response()->json([
            'success' => true,
            'veterinarios' => $veterinarios
        ]);
    }

    public function horariosDisponibles(Request $request)
    {
        $datos = $request->validate([
            'veterinario_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'turno' => 'required|in:mañana,tarde,noche',
        ]);

        // Horarios disponibles según el turno
        $horariosPorTurno = [
            'mañana' => [
                '08:00',
                '09:00',
                '10:00',
                '11:00',
            ],

            'tarde' => [
                '14:00',
                '15:00',
                '16:00',
                '17:00',
            ],

            'noche' => [
                '18:00',
                '19:00',
                '20:00',
            ],
        ];

        $horarios = $horariosPorTurno[$datos['turno']];

        // Buscar citas existentes del veterinario para esa fecha
        $citasOcupadas = Cita::where('veterinario_id', $datos['veterinario_id'])
            ->whereDate('fecha', $datos['fecha'])
            ->whereIn('hora', $horarios)
            ->where('estado', '!=', 'cancelada')
            ->pluck('hora')
            ->map(function ($hora) {
                return substr($hora, 0, 5);
            })
            ->toArray();

        // Construir respuesta
        $resultado = [];

        foreach ($horarios as $hora) {

            $resultado[] = [
                'hora' => $hora,
                'disponible' => !in_array($hora, $citasOcupadas),
            ];
        }

        return response()->json([
            'success' => true,
            'horarios' => $resultado,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $datos = $request->validate([
            'mascota_id' => 'required|exists:mascotas,id',
            'veterinario_id' => 'required|exists:users,id',
            'fecha' => 'required|date|after_or_equal:today',
            'turno' => 'required|in:mañana,tarde,noche',
            'hora' => 'required|date_format:H:i',
            'motivo' => 'required|string|max:255',
            'observaciones' => 'nullable|string',
        ]);
        $mascota = \App\Models\Mascota::where('id', $datos['mascota_id'])
            ->where('user_id', $user->id)
            ->first();
        if (!$mascota) {
            return response()->json([
                'success' => false,
                'message' => 'La mascota seleccionada no te pertenece.',
            ], 403);
        }
        $veterinario = \App\Models\User::where('id', $datos['veterinario_id'])
            ->where('role', 'veterinario')
            ->first();
        if (!$veterinario) {
            return response()->json([
                'success' => false,
                'message' => 'El veterinario seleccionado no es válido.',
            ], 422);
        }
        $horariosPorTurno = [
            'mañana' => ['08:00', '09:00', '10:00', '11:00'],
            'tarde' => ['14:00', '15:00', '16:00', '17:00'],
            'noche' => ['18:00', '19:00', '20:00'],
        ];
        if (!in_array($datos['hora'], $horariosPorTurno[$datos['turno']])) {
            return response()->json([
                'success' => false,
                'message' => 'La hora seleccionada no corresponde al turno.',
            ], 422);
        }
        $citaExistente = Cita::where('veterinario_id', $datos['veterinario_id'])
            ->whereDate('fecha', $datos['fecha'])
            ->where('hora', $datos['hora'])
            ->where('estado', '!=', 'cancelada')
            ->exists();
        if ($citaExistente) {
            return response()->json([
                'success' => false,
                'message' => 'El veterinario ya tiene una cita ocupada para esa fecha y hora.',
            ], 409);
        }
        $citaCliente = Cita::where('fecha', $datos['fecha'])
            ->where('hora', $datos['hora'])
            ->where('estado', '!=', 'cancelada')
            ->whereHas('mascota', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->exists();
        if ($citaCliente) {
            return response()->json([
                'success' => false,
                'message' => 'Ya tienes otra cita registrada para esa fecha y hora.',
            ], 409);
        }
        $cita = Cita::create([
            'mascota_id' => $datos['mascota_id'],
            'veterinario_id' => $datos['veterinario_id'],
            'fecha' => $datos['fecha'],
            'hora' => $datos['hora'],
            'turno' => $datos['turno'],
            'motivo' => $datos['motivo'],
            'estado' => 'pendiente',
            'observaciones' => $datos['observaciones'] ?? null,
        ]);
        $cita->load('mascota');
        return response()->json([
            'success' => true,
            'message' => 'Cita registrada correctamente.',
            'cita' => $cita,
        ]);
    }
}
