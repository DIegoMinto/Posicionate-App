<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Docente extends Model
{
    protected $table = 'docente';
    protected $primaryKey = 'id_docente';

    protected $fillable = [
        'ci',
        'extension_ci',
        'nombre',
        'apellido_p',
        'apellido_m',
        'fecha_nacimiento',
        'domicilio',
        'telefono_movil',
        'correo_electronico',
        'genero',
        'curriculum',
        'fotocarnet',
        'fotografia',
        'numero_cuenta_bancaria',
        'emite_factura',
        'programas_dar',
        'area',
        'id_ciudad',
        'id_institucion_egreso',
        'id_grado_academico',
        'id_profesion',
        'id_institucion_bancaria'
    ];

    // Ejemplo de relación: Para saber de qué ciudad es el docente
    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id_ciudad');
    }

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(InstitucionEgreso::class, 'id_institucion_egreso', 'id_institucion_egreso');
    }

    public function grado(): BelongsTo
    {
        return $this->belongsTo(GradoAcademico::class, 'id_grado_academico', 'id_grado_academico');
    }

    public function profesion(): BelongsTo
    {
        return $this->belongsTo(Profesion::class, 'id_profesion', 'id_profesion');
    }
    public function institucion_bancaria(): BelongsTo
    {
        return $this->belongsTo(InstitucionBancaria::class, 'id_institucion_bancaria', 'id_institucion_bancaria');
    }

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'id_docente', 'id_docente');
    }
}