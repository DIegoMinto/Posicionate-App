<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NumeroInstitucional extends Model
{
    protected $table = 'numero_institucional';

    protected $primaryKey = 'id_numero_institucional';

    protected $fillable = [
        'numero',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function personal(): HasOne
    {
        return $this->hasOne(
            Personal::class,
            'id_numero_institucional',
            'id_numero_institucional'
        );
    }
}
