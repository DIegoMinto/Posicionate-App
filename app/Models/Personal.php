<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
            'asesora_marketing' => 'Asesora de Marketing',
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


    public $timestamps = true;
    protected $casts = [
        'es_vigente' => 'boolean',
    ];
}