<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'noticia_id',
    ];

    /**
     * Usuario que dio el like.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Noticia a la que pertenece el like.
     */
    public function noticia(): BelongsTo
    {
        return $this->belongsTo(Noticia::class);
    }
}