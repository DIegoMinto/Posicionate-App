<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CursoEstudiante extends Model
{
    use HasFactory;

    protected $table = 'curso_estudiante';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $fillable = [
        'id_curso',
        'id_estudiante',
        'id_personal',
        'estado',
        'id_planes_pago',
        'id_descuento'
    ];


    public function plan(): BelongsTo
    {
        // OJO: Cambié PlanesPago por PlanPago para que coincida con tu modelo
        return $this->belongsTo(PlanesPago::class, 'id_planes_pago', 'id_planes_pago');
    }

    public function asesor(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'id_personal', 'id_personal');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }


    public function descuento(): BelongsTo
    {
        // Especificamos la llave foránea y la llave local por seguridad
        return $this->belongsTo(Descuento::class, 'id_descuento', 'id_descuentos');
    }


    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Estudiante::class, 'id_estudiante', 'id_estudiante');
    }
}