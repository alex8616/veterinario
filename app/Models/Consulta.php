<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consulta extends Model
{
    use HasFactory;

    protected $fillable = [
        'mascota_id',
        'veterinario_id',
        'cita_id',
        'fecha',
        'motivo',
        'diagnostico',
        'observaciones',
        'peso',
        'temperatura',
    ];

    protected $casts = [
        'fecha' => 'date',
        'peso' => 'decimal:2',
        'temperatura' => 'decimal:1',
    ];

    /**
     * Mascota atendida.
     */
    public function mascota(): BelongsTo
    {
        return $this->belongsTo(Mascota::class);
    }

    /**
     * Veterinario que realizó la consulta.
     */
    public function veterinario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'veterinario_id');
    }

    /**
     * Cita relacionada con la consulta.
     */
    public function cita(): BelongsTo
    {
        return $this->belongsTo(Cita::class);
    }
    
    public function tratamientos(): HasMany
    {
        return $this->hasMany(Tratamiento::class);
    }
}