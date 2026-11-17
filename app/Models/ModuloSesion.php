<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuloSesion extends Model
{
    protected $table = 'modulo_sesion';

    protected $primaryKey = 'id_sesion';

    protected $fillable = [
        'id_modulo',
        'numero_sesion',
        'titulo',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'observaciones',
    ];

    protected $casts = [
        'numero_sesion' => 'integer',
        'fecha' => 'date',
    ];

    public function modulo(): BelongsTo
    {
        return $this->belongsTo(
            Modulo::class,
            'id_modulo',
            'id_modulo'
        );
    }
}
