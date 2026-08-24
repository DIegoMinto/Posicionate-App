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

    // Relación con Persona (Ya la tenías)
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    // NUEVA: Relación con Sede
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'id_sede', 'id_sede');
    }


    public function getCargoNombreAttribute()
    {
        return [
            'gerente_marketing' => 'Gerente de Marketing',
            'supervisor_marketing' => 'Supervisor de Marketing',
            'coordinador_marketing' => 'Coordinador de Marketing',
            'asesor_marketing' => 'Asesor de Marketing',
            'supervisor_academico' => 'Supervisor Académico',
            'coordinador_academico' => 'Coordinador Académico',
            'asistente_academico' => 'Asistente Académico',
            'contador' => 'Contador',
            'asistente_contable' => 'Asistente Contable',
        ][$this->cargo] ?? $this->cargo;
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
        return $this->roles->contains('nombre', $nombre);
    }

    public function hasAnyRole(array $nombres): bool
    {
        return $this->roles->whereIn('nombre', $nombres)->isNotEmpty();
    }

    public function getRolesNombresAttribute()
    {
        return $this->roles->pluck('nombre_visible');
    }

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
        return $this->cargos->contains('nombre', $nombre);
    }

    public function hasAnyCargo(array $nombres): bool
    {
        return $this->cargos->whereIn('nombre', $nombres)->isNotEmpty();
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