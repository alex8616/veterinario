<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    public function index()
    {
        return view('perfil');
    }

    public function data()
    {
        $user=auth()->user();

        return response()->json([
            'success'=>true,
            'usuario'=>[
                'id'=>$user->id,
                'name'=>$user->name,
                'email'=>$user->email,
                'role'=>$user->role,
                'created_at'=>$user->created_at
            ]
        ]);
    }

    public function update(Request $request)
    {
        $user=auth()->user();

        $datos=$request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255|unique:users,email,'.$user->id,
            'password'=>'nullable|string|min:8|confirmed'
        ]);

        $user->name=$datos['name'];
        $user->email=$datos['email'];

        if(!empty($datos['password'])){
            $user->password=Hash::make($datos['password']);
        }

        $user->save();

        return response()->json([
            'success'=>true,
            'message'=>'Perfil actualizado correctamente.'
        ]);
    }
}