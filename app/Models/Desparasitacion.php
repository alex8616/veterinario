<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Desparasitacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'mascota_id',
        'veterinario_id',
        'producto',
        'fecha',
        'proxima_fecha',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'proxima_fecha' => 'date',
    ];

    /**
     * Mascota a la que pertenece la desparasitación.
     */
    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class);
    }

    /**
     * Veterinario que realizó la desparasitación.
     */
    public function veterinario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'veterinario_id');
    }
}