<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AreaDocente extends Model
{
    protected $table = 'area_docente';

    protected $primaryKey = 'id_area_docente';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function docentes(): BelongsToMany
    {
        return $this->belongsToMany(
            Docente::class,
            'docente_area_docente',
            'id_area_docente',
            'id_docente'
        )->withTimestamps();
    }
}
