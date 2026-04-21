<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'id_ciudad',
        'id_personal',
        'id_institucion_egreso',
        'id_grado_academico',
        'id_profesion'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    // --- RELACIONES ---

    // El personal que registró al estudiante
    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'id_personal', 'id_personal');
    }

    // Ciudad de origen/residencia
    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad', 'id_ciudad');
    }

    // Profesión del estudiante
    public function profesion(): BelongsTo
    {
        return $this->belongsTo(Profesion::class, 'id_profesion', 'id_profesion');
    }

    // Grado académico (Licenciado, Bachiller, etc)
    public function gradoAcademico(): BelongsTo
    {
        return $this->belongsTo(GradoAcademico::class, 'id_grado_academico', 'id_grado_academico');
    }

    // Colegio o Universidad de la que salió
    public function institucionEgreso(): BelongsTo
    {
        return $this->belongsTo(InstitucionEgreso::class, 'id_institucion_egreso', 'id_institucion_egreso');
    }
}