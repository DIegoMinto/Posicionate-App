<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Masterclass extends Model
{
    protected $table = 'masterclass';

    protected $primaryKey = 'id_masterclass';

    protected $fillable = [
        'id_curso',
        'id_docente',
        'titulo',
        'fecha_inicio',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
    ];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(
            Curso::class,
            'id_curso',
            'id_curso'
        );
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(
            Docente::class,
            'id_docente',
            'id_docente'
        );
    }
}