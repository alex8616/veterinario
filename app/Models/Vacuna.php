<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vacuna extends Model
{
    use HasFactory;

    protected $fillable = [
        'mascota_id',
        'veterinario_id',
        'nombre',
        'fecha_aplicacion',
        'proxima_dosis',
        'observaciones',
    ];

    protected $casts = [
        'fecha_aplicacion' => 'date',
        'proxima_dosis' => 'date',
    ];

    /**
     * Mascota a la que pertenece la vacuna.
     */
    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class);
    }

    /**
     * Veterinario que aplicó la vacuna.
     */
    public function veterinario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'veterinario_id');
    }
}