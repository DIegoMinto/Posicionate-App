<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pais extends Model
{
    protected $table = 'pais';
    protected $primaryKey = 'id_pais';
    protected $fillable = ['nombre', 'codigo_numero'];

    public function departamentos(): HasMany
    {
        return $this->hasMany(Departamento::class, 'id_pais', 'id_pais');
    }
    public function bancos()
    {
        return $this->belongsToMany(
            InstitucionBancaria::class,
            'banco_pais',
            'id_pais',
            'id_institucion_bancaria'
        );
    }
}