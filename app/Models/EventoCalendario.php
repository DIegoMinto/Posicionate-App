<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventoCalendario extends Model
{
    protected $table = 'eventos_calendario';

    protected $fillable = ['titulo', 'descripcion', 'tipo', 'fecha_inicio', 'fecha_fin', 'id_usuario'];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];
}