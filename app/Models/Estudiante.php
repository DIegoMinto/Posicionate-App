<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiante';
    protected $primaryKey = 'id_estudiante';

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

        'id_departamento',
        'ciudad_residencia',

        'id_institucion_egreso',
        'id_grado_academico',
        'id_profesion'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id_ciudad');
    }


    public function profesion(): BelongsTo
    {
        return $this->belongsTo(Profesion::class, 'id_profesion', 'id_profesion');
    }


    public function gradoAcademico(): BelongsTo
    {
        return $this->belongsTo(GradoAcademico::class, 'id_grado_academico', 'id_grado_academico');
    }


    public function institucionEgreso(): BelongsTo
    {
        return $this->belongsTo(InstitucionEgreso::class, 'id_institucion_egreso', 'id_institucion_egreso');
    }

    public function cursosEstudiante(): HasMany
    {
        return $this->hasMany(CursoEstudiante::class, 'id_estudiante', 'id_estudiante');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }
}