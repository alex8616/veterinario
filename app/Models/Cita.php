<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'mascota_id',
        'veterinario_id',
        'fecha',
        'hora',
        'motivo',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    /**
     * Mascota de la cita.
     */
    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class);
    }

    /**
     * Veterinario encargado de la cita.
     */
    public function veterinario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'veterinario_id');
    }

    public function consulta(): HasOne
    {
        return $this->hasOne(Consulta::class);
    }

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }
}