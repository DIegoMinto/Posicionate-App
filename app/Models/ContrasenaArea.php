<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContrasenaArea extends Model
{
    protected $fillable = ['area_id', 'contrasena_encriptada'];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
}