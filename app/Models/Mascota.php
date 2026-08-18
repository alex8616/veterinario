<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mascota extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nombre',
        'especie',
        'raza',
        'sexo',
        'fecha_nacimiento',
        'peso',
        'color',
        'observaciones',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'peso' => 'decimal:2',
    ];

    /**
     * Usuario propietario de la mascota.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class);
    }

    public function vacunas(): HasMany
    {
        return $this->hasMany(Vacuna::class);
    }

    public function desparasitaciones(): HasMany
    {
        return $this->hasMany(Desparasitacion::class);
    }

    public function tratamientos(): HasMany
    {
        return $this->hasMany(Tratamiento::class);
    }
}