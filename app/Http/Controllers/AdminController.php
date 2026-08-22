<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mascota;

class AdminController extends Controller
{
    public function clientes()
    {
        return view('admin.clientes');
    }

    public function clientesData()
    {
        $clientes = User::where('role', 'cliente')
            ->with('mascotas')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'clientes' => $clientes
        ]);
    }

    public function guardarUsuario(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255|unique:users,email',
            'password'=>'required|string|min:6',
            'role'=>'required|in:cliente,veterinario,admin',
        ]);

        $usuario=User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'role'=>$request->role,
        ]);

        return response()->json([
            'success'=>true,
            'usuario'=>$usuario,
            'message'=>'Usuario registrado correctamente.'
        ]);
    }
}
