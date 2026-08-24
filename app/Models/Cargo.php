<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Cargo extends Model
{
    protected $table = 'cargos';
    protected $primaryKey = 'id_cargo';
    protected $fillable = ['nombre', 'nombre_visible'];

    public function personal(): BelongsToMany
    {
        return $this->belongsToMany(Personal::class, 'personal_cargo', 'id_cargo', 'id_personal');
    }
}