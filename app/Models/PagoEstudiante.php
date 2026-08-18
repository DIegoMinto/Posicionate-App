<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PagoEstudiante extends Model
{
    use HasFactory;

    protected $table = 'pagos_estudiante';
    protected $primaryKey = 'id_pagos_estudiante';
    public $timestamps = false;
    protected $fillable = [
        'id_curso_estudiante',
        'id_pago_original',
        'detalle',
        'monto_pagar',
        'monto_pagado',
        'fecha_programada',
        'fecha_pagada',
        'estado',
        'numero_recibo',
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

    public static function generarNumeroRecibo(): string
    {
        $ultimo = self::whereNotNull('numero_recibo')
            ->lockForUpdate()
            ->orderByDesc('numero_recibo')
            ->value('numero_recibo');

        $siguiente = $ultimo ? ((int) $ultimo) + 1 : 1000;

        return str_pad($siguiente, 6, '0', STR_PAD_LEFT);
    }
    public function divisiones()
    {
        return $this->hasMany(PagoEstudiante::class, 'id_pago_original', 'id_pagos_estudiante')
            ->orderBy('id_pagos_estudiante');
    }

    public function original()
    {
        return $this->belongsTo(PagoEstudiante::class, 'id_pago_original', 'id_pagos_estudiante');
    }
}