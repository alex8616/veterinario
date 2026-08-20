<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Noticia extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titulo',
        'slug',
        'contenido',
        'imagen',
        'estado',
    ];

    /**
     * Usuario que publicó la noticia.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Comentarios de la noticia.
     */
    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class);
    }

    /**
     * Likes de la noticia.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }


}