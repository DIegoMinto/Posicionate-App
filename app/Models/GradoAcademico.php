<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradoAcademico extends Model
{
    protected $table = 'grado_academico';
    protected $primaryKey = 'id_grado_academico';
    protected $fillable = ['nombre'];
}
