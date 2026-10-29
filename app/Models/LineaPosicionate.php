<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;

class LineaPosicionate extends Model
{
    protected $table = 'lineas_posicionate';

    protected $fillable = [
        'celular',
        'pertenencia_tipo',
        'id_persona',
        'pertenencia_texto',
        'correo',
        'contrasena',
        'fecha_ultima_recarga',
        'fecha_siguiente_recarga',
    ];

    protected function casts(): array
    {
        return [
            'contrasena' => 'encrypted',
            'fecha_ultima_recarga' => 'date',
            'fecha_siguiente_recarga' => 'date',
        ];
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(
            Persona::class,
            'id_persona',
            'id_persona'
        );
    }

    public function getPertenecienteAttribute(): string
    {
        if ($this->pertenencia_tipo === 'personal') {
            return $this->persona
                ? trim($this->persona->nombre . ' ' .
                    $this->persona->apellido_p . ' ' .
                    $this->persona->apellido_m)
                : 'Personal no asignado';
        }

        return $this->pertenencia_texto ?? 'Sin asignar';
    }
}