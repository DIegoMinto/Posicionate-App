<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Persona extends Model
{
    protected $table = 'persona';

    protected $primaryKey = 'id_persona';

    protected $fillable = [
        'nombre',
        'apellido_p',
        'apellido_m',
        'ci',
        'extension_ci',
        'fecha_nacimiento',
        'domicilio',
        'enlace_ubicacion_maps',
        'telefono_movil',
        'correo_electronico',
        'genero',
        'curriculum',
        'foto_carnet',
        'fotografia',
        'numero_cuenta_bancaria',
        'referencia_familiar_1',
        'celular_familiar_1',
        'referencia_familiar_2',
        'celular_familiar_2',
        'habilidades_tecnicas',
        'habilidades_blandas',
        'id_ciudad',
        'id_institucion_egreso',
        'id_grado_academico',
        'id_profesion',
        'id_institucion_bancaria'
    ];


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
    public function personal()
    {
        return $this->hasOne(\App\Models\Personal::class, 'id_persona', 'id_persona');
    }
}