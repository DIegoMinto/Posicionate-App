<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rol extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    protected $fillable = ['nombre', 'nombre_visible'];

    public function personal(): BelongsToMany
    {
        return $this->belongsToMany(Personal::class, 'personal_rol', 'id_rol', 'id_personal');
    }
}