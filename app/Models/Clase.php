<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    protected $table = 'clase';
    protected $primaryKey = 'id_clase';

    protected $fillable = [
        'nombre',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'tipo',
        'id_curso'
    ];

    // Una clase pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }
}
