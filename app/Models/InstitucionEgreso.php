<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitucionEgreso extends Model
{
    protected $table = 'institucion_egreso';
    protected $primaryKey = 'id_institucion_egreso';
    protected $fillable = ['nombre'];
}
