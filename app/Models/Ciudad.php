<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciudad extends Model
{
    protected $table = 'ciudad';
    protected $primaryKey = 'id_ciudad';
    protected $fillable = ['nombre', 'id_departamento'];

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class, 'id_departamento', 'id_departamento');
    }

    public function personas(): HasMany
    {
        return $this->hasMany(Persona::class, 'id_ciudad', 'id_ciudad');
    }
}
