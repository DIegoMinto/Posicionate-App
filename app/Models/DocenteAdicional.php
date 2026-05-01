<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocenteAdicional extends Model
{
    use HasFactory;

    protected $table = 'docentes_adicionales';
    protected $primaryKey = 'id_docentes_adicionales';
    protected $fillable = [
        'id_docente',
        'id_curso'
    ];


    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente', 'id_docente');
    }
}