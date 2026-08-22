<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Mail\NuevaCitaMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Consulta;
use App\Models\Vacuna;
use App\Models\Mascota;
use App\Models\Desparasitacion;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $cita->load([
            'mascota',
            'veterinario',
        ]);
        Mail::to($cita->veterinario->email)->send(new NuevaCitaMail($cita));

        return response()->json([
            'success' => true,
            'message' => 'Cita registrada correctamente.',
            'cita' => $cita,
        ]);
    }

    public function cancelar(Cita $cita)
    {
        $cita->load('mascota');

        if ($cita->mascota->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para cancelar esta cita.'
            ],403);
        }

        if (!in_array($cita->estado,['pendiente','confirmada'])) {
            return response()->json([
                'success' => false,
                'message' => 'Esta cita no puede ser cancelada.'
            ],422);
        }

        $cita->update([
            'estado' => 'cancelada'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'La cita fue cancelada correctamente.'
        ]);
    }

    public function citasVeterinario()
    {
        return view('veterinario.citas');
    }

    public function citasVeterinarioData()
    {
        $citas=Cita::where('veterinario_id',auth()->id())
            ->with('mascota')
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        return response()->json([
            'success'=>true,
            'citas'=>$citas
        ]);
    }

    public function detalleCitaVeterinario(Cita $cita)
    {
        if($cita->veterinario_id!==auth()->id()){
            return response()->json([
                'success'=>false,
                'message'=>'No tienes permiso para consultar esta cita.'
            ],403);
        }

        $cita->load([
            'mascota',
            'veterinario'
        ]);

        return response()->json([
            'success'=>true,
            'cita'=>$cita
        ]);
    }

    public function iniciarConsulta(Cita $cita)
    {
        if($cita->veterinario_id!==auth()->id()){
            return response()->json([
                'success'=>false,
                'message'=>'No tienes permiso para esta cita.'
            ],403);
        }

        if($cita->estado==='cancelada'){
            return response()->json([
                'success'=>false,
                'message'=>'No puedes iniciar una consulta para una cita cancelada.'
            ],422);
        }

        if($cita->consulta){
            return response()->json([
                'success'=>false,
                'message'=>'Esta cita ya tiene una consulta registrada.'
            ],422);
        }

        $cita->load('mascota');

        return response()->json([
            'success'=>true,
            'cita'=>$cita
        ]);
    }

    public function crearConsulta(Cita $cita)
    {
        if($cita->veterinario_id!==auth()->id()){
            return response()->json([
                'success'=>false,
                'message'=>'No tienes permiso para esta cita.'
            ],403);
        }

        if($cita->estado==='cancelada'){
            return response()->json([
                'success'=>false,
                'message'=>'No puedes iniciar una consulta para una cita cancelada.'
            ],422);
        }

        if($cita->consulta){
            return response()->json([
                'success'=>false,
                'message'=>'Esta cita ya tiene una consulta registrada.'
            ],422);
        }

        $cita->load('mascota');

        return response()->json([
            'success'=>true,
            'cita'=>$cita
        ]);
    }

    public function guardarConsulta(Request $request,Cita $cita)
    {
        try{
            if($cita->veterinario_id!==auth()->id()){
                return response()->json([
                    'success'=>false,
                    'message'=>'No tienes permiso para esta cita.'
                ],403);
            }

            if($cita->estado==='cancelada'){
                return response()->json([
                    'success'=>false,
                    'message'=>'No puedes registrar una consulta para una cita cancelada.'
                ],422);
            }

            if($cita->consulta){
                return response()->json([
                    'success'=>false,
                    'message'=>'Esta cita ya tiene una consulta registrada.'
                ],422);
            }

            $validated=$request->validate([
                'mascota_id'=>'required|exists:mascotas,id',
                'motivo'=>'required|string|max:255',
                'diagnostico'=>'nullable|string',
                'observaciones'=>'nullable|string',
                'peso'=>'nullable|numeric|min:0',
                'temperatura'=>'nullable|numeric|min:0',
            ]);

            if((int)$validated['mascota_id']!==(int)$cita->mascota_id){
                return response()->json([
                    'success'=>false,
                    'message'=>'La mascota no corresponde a la cita.'
                ],422);
            }

            DB::beginTransaction();

            $consulta=Consulta::create([
                'mascota_id'=>$cita->mascota_id,
                'veterinario_id'=>$cita->veterinario_id,
                'cita_id'=>$cita->id,
                'fecha'=>now()->toDateString(),
                'motivo'=>$validated['motivo'],
                'diagnostico'=>$validated['diagnostico']??null,
                'observaciones'=>$validated['observaciones']??null,
                'peso'=>$validated['peso']??null,
                'temperatura'=>$validated['temperatura']??null,
            ]);

            $cita->update([
                'estado'=>'atendida',
            ]);

            DB::commit();

            return response()->json([
                'success'=>true,
                'message'=>'Consulta registrada correctamente.',
                'consulta'=>$consulta,
            ]);
        }catch(\Throwable $e){
            DB::rollBack();

            return response()->json([
                'success'=>false,
                'message'=>$e->getMessage(),
                'file'=>$e->getFile(),
                'line'=>$e->getLine(),
            ],500);
        }
    }

    public function consulta(Cita $cita)
    {
        if($cita->veterinario_id!==auth()->id()){
            return response()->json([
                'success'=>false,
                'message'=>'No tienes permiso para esta cita.'
            ],403);
        }

        $cita->load([
            'mascota',
            'consulta.veterinario',
            'consulta.tratamientos',
        ]);

        if(!$cita->consulta){
            return response()->json([
                'success'=>false,
                'message'=>'Esta cita todavía no tiene una consulta registrada.'
            ],404);
        }

        return response()->json([
            'success'=>true,
            'cita'=>$cita,
            'consulta'=>$cita->consulta,
        ]);
    }

    public function guardarTratamiento(Request $request, Consulta $consulta)
    {
        if($consulta->veterinario_id!==auth()->id()){
            return response()->json([
                'success'=>false,
                'message'=>'No tienes permiso para registrar este tratamiento.'
            ],403);
        }

        $request->validate([
            'nombre'=>'required|string|max:255',
            'descripcion'=>'nullable|string',
            'fecha_inicio'=>'required|date',
            'fecha_fin'=>'nullable|date|after_or_equal:fecha_inicio',
            'estado'=>'required|string|max:50',
            'observaciones'=>'nullable|string',
        ]);

        $tratamiento=$consulta->tratamientos()->create([
            'mascota_id'=>$consulta->mascota_id,
            'veterinario_id'=>$consulta->veterinario_id,
            'nombre'=>$request->nombre,
            'descripcion'=>$request->descripcion,
            'fecha_inicio'=>$request->fecha_inicio,
            'fecha_fin'=>$request->fecha_fin,
            'estado'=>$request->estado,
            'observaciones'=>$request->observaciones,
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Tratamiento registrado correctamente.',
            'tratamiento'=>$tratamiento,
        ]);
    }

    public function mostrarConsulta(Consulta $consulta)
    {
        if($consulta->veterinario_id!==auth()->id()){
            return response()->json([
                'success'=>false,
                'message'=>'No tienes permiso para consultar esta información.'
            ],403);
        }

        $consulta->load([
            'mascota',
            'veterinario',
            'tratamientos',
        ]);

        return response()->json([
            'success'=>true,
            'consulta'=>$consulta,
        ]);
    }

    public function guardarVacuna(Request $request, Consulta $consulta)
    {
        if ($consulta->veterinario_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para registrar esta vacuna.'
            ], 403);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_aplicacion' => 'required|date',
            'proxima_dosis' => 'nullable|date|after_or_equal:fecha_aplicacion',
            'observaciones' => 'nullable|string',
        ]);

        $vacuna = Vacuna::create([
            'mascota_id' => $consulta->mascota_id,
            'veterinario_id' => $consulta->veterinario_id,
            'nombre' => $request->nombre,
            'fecha_aplicacion' => $request->fecha_aplicacion,
            'proxima_dosis' => $request->proxima_dosis,
            'observaciones' => $request->observaciones,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Vacuna registrada correctamente.',
            'vacuna' => $vacuna->load('veterinario'),
        ]);
    }

    public function vacunasMascota(Mascota $mascota)
    {
        $vacunas = $mascota->vacunas()
            ->with('veterinario')
            ->orderByDesc('fecha_aplicacion')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'success' => true,
            'vacunas' => $vacunas,
        ]);
    }

    public function guardarDesparasitacion(Request $request, Consulta $consulta)
    {
        if ($consulta->veterinario_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para registrar esta desparasitación.'
            ], 403);
        }

        $request->validate([
            'producto' => 'required|string|max:255',
            'fecha' => 'required|date',
            'proxima_fecha' => 'nullable|date|after_or_equal:fecha',
            'observaciones' => 'nullable|string',
        ]);

        $desparasitacion = Desparasitacion::create([
            'mascota_id' => $consulta->mascota_id,
            'veterinario_id' => $consulta->veterinario_id,
            'producto' => $request->producto,
            'fecha' => $request->fecha,
            'proxima_fecha' => $request->proxima_fecha,
            'observaciones' => $request->observaciones,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Desparasitación registrada correctamente.',
            'desparasitacion' => $desparasitacion->load('veterinario'),
        ]);
    }

    public function desparasitacionesMascota(Mascota $mascota)
    {
        $desparasitaciones = $mascota->desparasitaciones()
            ->with('veterinario')
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();

        return response()->json([
            'success' => true,
            'desparasitaciones' => $desparasitaciones,
        ]);
    }

    public function citasVeterinarioAgenda()
    {
        return view('veterinario.agenda');
    }

    public function citasVeterinarioFecha($fecha)
    {
        $citas = Cita::where('veterinario_id', auth()->id())
            ->whereDate('fecha', $fecha)
            ->with('mascota')
            ->orderBy('hora')
            ->get();

        return response()->json([
            'success' => true,
            'citas' => $citas,
        ]);
    }

    public function historiaClinica()
    {
        return view('veterinario.historia-clinica');
    }

    public function historiaClinicaMascota(Mascota $mascota)
    {
        $mascota->load([
            'user',

            'consultas' => function ($query) {
                $query->with('veterinario')
                    ->orderByDesc('fecha')
                    ->orderByDesc('id');
            },

            'vacunas' => function ($query) {
                $query->with('veterinario')
                    ->orderByDesc('fecha_aplicacion')
                    ->orderByDesc('id');
            },

            'desparasitaciones' => function ($query) {
                $query->with('veterinario')
                    ->orderByDesc('fecha')
                    ->orderByDesc('id');
            },

            'tratamientos' => function ($query) {
                $query->with('veterinario')
                    ->orderByDesc('fecha_inicio')
                    ->orderByDesc('id');
            },
        ]);

        return response()->json([
            'success' => true,
            'mascota' => $mascota,
        ]);
    }

    public function clientesHistoriaClinica(Request $request)
    {
        $buscar = $request->input('buscar');

        $clientes = User::where('role', 'cliente')
            ->when($buscar, function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('name', 'like', "%{$buscar}%")
                    ->orWhere('email', 'like', "%{$buscar}%");
                });
            })
            ->with([
                'mascotas' => function ($query) {
                    $query->orderBy('nombre');
                }
            ])
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'clientes' => $clientes,
        ]);
    }

    public function exportarHistoriaClinicaPDF(Mascota $mascota)
    {
        $mascota->load([
            'user',

            'consultas' => function ($query) {
                $query->with('veterinario')
                    ->orderByDesc('fecha')
                    ->orderByDesc('id');
            },

            'vacunas' => function ($query) {
                $query->with('veterinario')
                    ->orderByDesc('fecha_aplicacion')
                    ->orderByDesc('id');
            },

            'desparasitaciones' => function ($query) {
                $query->with('veterinario')
                    ->orderByDesc('fecha')
                    ->orderByDesc('id');
            },

            'tratamientos' => function ($query) {
                $query->with('veterinario')
                    ->orderByDesc('fecha_inicio')
                    ->orderByDesc('id');
            },
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'veterinario.pdf.historia-clinica',
            compact('mascota')
        );

        return $pdf->stream(
            'historia-clinica-' . $mascota->nombre . '.pdf'
        );
    }

    public function historiaClinicaCliente(User $cliente)
    {
        $cliente->load([
            'mascotas' => function ($query) {

                $query->orderBy('nombre');

                $query->with([
                    'consultas' => function ($query) {
                        $query->with('veterinario')
                            ->orderByDesc('fecha')
                            ->orderByDesc('id');
                    },

                    'vacunas' => function ($query) {
                        $query->with('veterinario')
                            ->orderByDesc('fecha_aplicacion')
                            ->orderByDesc('id');
                    },

                    'desparasitaciones' => function ($query) {
                        $query->with('veterinario')
                            ->orderByDesc('fecha')
                            ->orderByDesc('id');
                    },

                    'tratamientos' => function ($query) {
                        $query->with('veterinario')
                            ->orderByDesc('fecha_inicio')
                            ->orderByDesc('id');
                    },
                ]);
            }
        ]);

        return response()->json([
            'success' => true,
            'cliente' => $cliente,
        ]);
    }

    public function historiaClinicaClientePdf(User $cliente)
    {
        $cliente->load([
            'mascotas' => function ($query) {

                $query->orderBy('nombre');

                $query->with([
                    'consultas' => function ($query) {
                        $query->with('veterinario')
                            ->orderByDesc('fecha')
                            ->orderByDesc('id');
                    },

                    'vacunas' => function ($query) {
                        $query->with('veterinario')
                            ->orderByDesc('fecha_aplicacion')
                            ->orderByDesc('id');
                    },

                    'desparasitaciones' => function ($query) {
                        $query->with('veterinario')
                            ->orderByDesc('fecha')
                            ->orderByDesc('id');
                    },

                    'tratamientos' => function ($query) {
                        $query->with('veterinario')
                            ->orderByDesc('fecha_inicio')
                            ->orderByDesc('id');
                    },
                ]);
            }
        ]);

        $pdf = Pdf::loadView(
            'veterinario.pdf.historia-clinica-cliente',
            compact('cliente')
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream(
            'historial-clinico-' .
            \Illuminate\Support\Str::slug($cliente->name) .
            '.pdf'
        );
    }
}
