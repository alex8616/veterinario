<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function mascotas(): HasMany
    {
        return $this->hasMany(Mascota::class);
    }

    public function citasComoVeterinario(): HasMany
    {
        return $this->hasMany(Cita::class, 'veterinario_id');
    }

    public function consultasComoVeterinario(): HasMany
    {
        return $this->hasMany(Consulta::class, 'veterinario_id');
    }

    public function vacunasComoVeterinario(): HasMany
    {
        return $this->hasMany(Vacuna::class, 'veterinario_id');
    }

    public function desparasitacionesComoVeterinario(): HasMany
    {
        return $this->hasMany(Desparasitacion::class, 'veterinario_id');
    }

    public function tratamientosComoVeterinario(): HasMany
    {
        return $this->hasMany(Tratamiento::class, 'veterinario_id');
    }

    public function noticias(): HasMany
    {
        return $this->hasMany(Noticia::class);
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
}
