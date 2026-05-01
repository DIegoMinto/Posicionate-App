<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanCuotaDetalle extends Model
{
    protected $table = 'plan_cuota_detalle';

    protected $primaryKey = 'id_plan_cuota_detalle';

    protected $fillable = [
        'nro_cuota',
        'monto_cuota',
        'fecha_vencimiento',
        'detalle',
        'id_planes_pago'
    ];

    public $timestamps = true;
}