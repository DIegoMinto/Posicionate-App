<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modulo extends Model
{
    protected $table = 'modulo';

    protected $primaryKey = 'id_modulo';

    protected $fillable = [
        'id_curso',
        'id_docente',
        'nombre',
        'orden',
        'contenido_academico',
        'fecha_inicio',
        'fecha_fin',
        'cantidad_sesiones',
        'dias_clase',
        'hora_inicio',
        'hora_fin',
        'dias_descanso',
        'fecha_inicio_descanso',
        'fecha_fin_descanso',
    ];

    protected $casts = [
        'orden' => 'integer',
        'cantidad_sesiones' => 'integer',
        'dias_clase' => 'array',
        'fecha_inicio_descanso' => 'date',
        'fecha_fin_descanso' => 'date',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(
            Curso::class,
            'id_curso',
            'id_curso'
        );
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(
            Docente::class,
            'id_docente',
            'id_docente'
        );
    }

    public function sesiones(): HasMany
    {
        return $this->hasMany(
            ModuloSesion::class,
            'id_modulo',
            'id_modulo'
        );
    }
}
