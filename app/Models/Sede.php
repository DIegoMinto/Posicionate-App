<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $table = 'sede';
    protected $primaryKey = 'id_sede';
    protected $fillable = ['nombre', 'direccion', 'telefono'];

    public function personal()
    {
        return $this->hasMany(Personal::class, 'id_sede', 'id_sede');
    }
}