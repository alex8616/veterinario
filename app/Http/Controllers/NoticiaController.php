<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Like;
use App\Models\Comentario;
use Illuminate\Http\Request;

class NoticiaController extends Controller
{
    public function index()
    {
        return view('noticias.index');
    }

    public function data()
    {
        $noticias=Noticia::where('estado','publicada')
            ->with('user:id,name')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success'=>true,
            'noticias'=>$noticias
        ]);
    }

    public function show(Noticia $noticia)
    {
        if($noticia->estado!=='publicada'){
            abort(404);
        }

        $noticia->load([
            'user:id,name',
            'comentarios.user:id,name',
            'likes'
        ]);

        $liked=$noticia->likes()
            ->where('user_id',auth()->id())
            ->exists();

        return response()->json([
            'success'=>true,
            'noticia'=>$noticia,
            'liked'=>$liked,
            'totalLikes'=>$noticia->likes->count()
        ]);
    }

    public function like(Noticia $noticia)
    {
        if($noticia->estado!=='publicada'){
            return response()->json([
                'success'=>false,
                'message'=>'La noticia no está disponible.'
            ],404);
        }

        $like=Like::where('user_id',auth()->id())
            ->where('noticia_id',$noticia->id)
            ->first();

        if($like){
            $like->delete();
            $liked=false;
        }else{
            Like::create([
                'user_id'=>auth()->id(),
                'noticia_id'=>$noticia->id
            ]);
            $liked=true;
        }

        $totalLikes=$noticia->likes()->count();

        return response()->json([
            'success'=>true,
            'liked'=>$liked,
            'totalLikes'=>$totalLikes
        ]);
    }

    public function comentarios(Noticia $noticia)
    {
        if($noticia->estado!=='publicada'){
            abort(404);
        }

        $comentarios=$noticia->comentarios()
            ->with('user:id,name')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'success'=>true,
            'comentarios'=>$comentarios
        ]);
    }

    public function guardarComentario(Request $request,Noticia $noticia)
    {
        if($noticia->estado!=='publicada'){
            return response()->json([
                'success'=>false,
                'message'=>'La noticia no está disponible.'
            ],404);
        }

        $request->validate([
            'contenido'=>'required|string|max:1000'
        ]);

        $comentario=Comentario::create([
            'user_id'=>auth()->id(),
            'noticia_id'=>$noticia->id,
            'contenido'=>$request->contenido
        ]);

        $comentario->load('user:id,name');

        return response()->json([
            'success'=>true,
            'comentario'=>$comentario
        ]);
    }

    public function eliminarComentario(Noticia $noticia,Comentario $comentario)
    {
        if($noticia->estado!=='publicada'){
            return response()->json([
                'success'=>false,
                'message'=>'La noticia no está disponible.'
            ],404);
        }

        if($comentario->noticia_id!==$noticia->id){
            return response()->json([
                'success'=>false,
                'message'=>'El comentario no pertenece a esta noticia.'
            ],403);
        }

        if($comentario->user_id!==auth()->id()){
            return response()->json([
                'success'=>false,
                'message'=>'No puedes eliminar este comentario.'
            ],403);
        }

        $comentario->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Comentario eliminado correctamente.'
        ]);
    }
}