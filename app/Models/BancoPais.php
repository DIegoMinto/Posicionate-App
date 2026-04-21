<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BancoPais extends Model
{
    protected $table = 'banco_pais';
    protected $primaryKey = 'id_banco_pais';

    protected $fillable = [
        'id_pais',
        'id_institucion_bancaria'
    ];

    // Relación hacia el País
    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class, 'id_pais', 'id_pais');
    }

    // Relación hacia el Banco
    public function banco(): BelongsTo
    {
        return $this->belongsTo(InstitucionBancaria::class, 'id_institucion_bancaria', 'id_institucion_bancaria');
    }
}