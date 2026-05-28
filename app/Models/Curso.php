<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Curso extends Model
{
    protected $table = 'curso';
    protected $primaryKey = 'id_curso';

    protected $fillable = [
        'codigo_curso',
        'nombre',
        'imagen_formulario',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'inscritos',
        'pre_inscritos',
        'id_docente',
        'id_institucion',
        'id_sede',
        'tipo',
        'costo_matricula'
    ];

    // Relaciones para que Laravel haga la magia
    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class, 'id_docente', 'id_docente');
    }

    public function docentesAdicionales()
    {
        return $this->hasMany(DocenteAdicional::class, 'id_curso', 'id_curso');
    }

    public function institucion(): BelongsTo
    {
        return $this->belongsTo(Institucion::class, 'id_institucion', 'id_institucion');
    }
    public function modulos()
    {
        return $this->hasMany(Modulo::class, 'id_curso');
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'id_sede', 'id_sede');
    }

    public function clases()
    {
        return $this->hasMany(Clase::class, 'id_curso', 'id_curso');
    }

    public function estudiantes()
    {
        return $this->belongsToMany(
            Estudiante::class,
            'curso_estudiante',
            'id_curso',
            'id_estudiante'
        )->withPivot('estado')->withTimestamps();
    }

    public function planes()
    {
        return $this->hasMany(PlanesPago::class, 'id_curso');
    }

}