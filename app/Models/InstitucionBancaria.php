<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitucionBancaria extends Model
{
    protected $table = 'institucion_bancaria';
    protected $primaryKey = 'id_institucion_bancaria';
    protected $fillable = ['nombre'];

    // Relación Muchos a Muchos con Pais a través de banco_pais
    public function paises()
    {
        return $this->belongsToMany(Pais::class, 'banco_pais', 'id_institucion_bancaria', 'id_pais');
    }
}