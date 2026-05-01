<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoEstudiante extends Model
{
    use HasFactory;

    protected $table = 'pagos_estudiante';
    protected $primaryKey = 'id_pagos_estudiante';
    public $timestamps = false;
    protected $fillable = [
        'id_curso_estudiante',
        'detalle',
        'monto_pagar',
        'fecha_programada',
        'fecha_pagada',
        'monto_pagado',
        'estado'
    ];

    protected $casts = [
        'fecha_programada' => 'date',
        'fecha_pagada' => 'date',
        'monto_pagar' => 'decimal:2'
    ];

    public function cursoEstudiante()
    {
        return $this->belongsTo(CursoEstudiante::class, 'id_curso_estudiante');
    }
}