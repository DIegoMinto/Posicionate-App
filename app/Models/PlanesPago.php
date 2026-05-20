<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanesPago extends Model
{
    protected $table = 'planes_pago';
    protected $primaryKey = 'id_planes_pago';

    protected $fillable = [
        'id_curso',
        'nombre',
        'incluye_matricula',
        'precio_base'
    ];

    public $timestamps = false;

    public function detalles()
    {
        return $this->hasMany(PlanCuotaDetalle::class, 'id_planes_pago', 'id_planes_pago');
    }
    public function curso()
    {
        return $this->belongsTo(
            Curso::class,
            'id_curso',
            'id_curso'
        );
    }
}