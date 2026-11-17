<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramacionAcademica extends Model
{
    protected $table = 'programacion_academica';

    protected $primaryKey = 'id_programacion';

    protected $fillable = [
        'id_curso',
        'duracion_meses',
        'cantidad_modulos',
        'dias_clase',
        'hora_inicio',
        'hora_fin',
        'carga_horaria',
        'id_docente_coordinador',
    ];

    protected $casts = [
        'dias_clase' => 'array',
        'duracion_meses' => 'integer',
        'cantidad_modulos' => 'integer',
        'carga_horaria' => 'decimal:2',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    public function coordinador(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'id_docente_coordinador', 'id_docente');
    }
}
