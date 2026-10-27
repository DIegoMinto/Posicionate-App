<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistroRetiroAbandono extends Model
{
    protected $table = 'registros_retiro_abandono';

    protected $fillable = [
        'id_curso_estudiante',
        'tipo',
        'fecha_registro',
        'modulos_cursados',
        'detalle',
        'nombre_estudiante',
        'ci_estudiante',
        'telefono_estudiante',
        'nombre_curso',
        'monto_total',
        'monto_pagado',
        'saldo_pendiente',
        'id_usuario',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
        'monto_total' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
        'pdf_url',
        'pdf_public_id',
    ];

    public function inscripcion(): BelongsTo
    {
        return $this->belongsTo(
            CursoEstudiante::class,
            'id_curso_estudiante',
            'id'
        );
    }
}