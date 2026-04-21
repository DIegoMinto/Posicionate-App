<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departamento extends Model
{
    protected $table = 'departamento';
    protected $primaryKey = 'id_departamento';
    protected $fillable = ['nombre', 'extension_ci', 'id_pais'];

    public function pais(): BelongsTo
    {
        return $this->belongsTo(Pais::class, 'id_pais', 'id_pais');
    }

    public function ciudades(): HasMany
    {
        return $this->hasMany(Ciudad::class, 'id_departamento', 'id_departamento');
    }
}