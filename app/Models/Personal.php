<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Personal extends Authenticatable
{
    use Notifiable;

    protected $table = 'personal';
    protected $primaryKey = 'id_personal';

    protected $fillable = [
        'id_persona',
        'codigo_personal',
        'cargo',
        'user',
        'password',
        'rol',
        'id_sede',
        'es_vigente',
        'instance_name'
    ];

    protected $hidden = [
        'password',
    ];

    public function getAuthPasswordName()
    {
        return 'password';
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'id_sede', 'id_sede');
    }

    public function getCargoNombreAttribute()
    {
        return $this->cargos
            ->pluck('nombre_visible')
            ->implode(', ');
    }

    public function getRolNombreAttribute()
    {
        return [
            'super_admin' => 'Super Administrador',
            'admin' => 'Administrador',
            'user' => 'Usuario',
            'viewer' => 'Solo Lectura',
        ][$this->rol] ?? $this->rol;
    }

    public function cursoEstudiantes()
    {
        return $this->hasMany(
            \App\Models\CursoEstudiante::class,
            'id_personal',
            'id_personal'
        );
    }

    // RELACIÓN DE ROLES
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Rol::class,
            'personal_rol',
            'id_personal',
            'id_rol'
        )->withTimestamps();
    }

    public function hasRole(string $nombre): bool
    {
        return $this->rol === $nombre || $this->roles->contains('nombre', $nombre);
    }

    public function hasAnyRole(array $nombres): bool
    {
        // Revisa columna estática 'rol'
        if (in_array($this->rol, $nombres)) {
            return true;
        }

        // Revisa la relación pivot N:M en colecciones
        return $this->roles->contains(function ($role) use ($nombres) {
            return in_array($role->nombre, $nombres);
        });
    }

    public function getRolesNombresAttribute()
    {
        return $this->roles->pluck('nombre_visible');
    }

    // RELACIÓN DE CARGOS
    public function cargos(): BelongsToMany
    {
        return $this->belongsToMany(
            Cargo::class,
            'personal_cargo',
            'id_personal',
            'id_cargo'
        )->withTimestamps();
    }

    public function hasCargo(string $nombre): bool
    {
        return $this->cargo === $nombre || $this->cargos->contains('nombre', $nombre);
    }

    public function hasAnyCargo(array $nombres): bool
    {
        // Revisa columna estática 'cargo'
        if (in_array($this->cargo, $nombres)) {
            return true;
        }

        // Revisa la relación pivot N:M en colecciones
        return $this->cargos->contains(function ($cargo) use ($nombres) {
            return in_array($cargo->nombre, $nombres);
        });
    }

    public function getCargosNombresAttribute()
    {
        return $this->cargos->pluck('nombre_visible')->implode(', ');
    }

    public $timestamps = true;
    protected $casts = [
        'es_vigente' => 'boolean',
    ];
}