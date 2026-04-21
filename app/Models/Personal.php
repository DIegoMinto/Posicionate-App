<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Opcional, para tipado

class Personal extends Authenticatable
{
    use Notifiable;

    protected $table = 'personal';
    protected $primaryKey = 'id_personal';

    protected $fillable = [
        'id_persona',
        'codigo_personal',
        'cargo',
        'user',
        'password',
        'rol',
        'id_sede',
        'es_vigente',
        'instance_name'
    ];

    protected $hidden = [
        'password',
    ];

    public function getAuthPasswordName()
    {
        return 'password';
    }

    // Relación con Persona (Ya la tenías)
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona', 'id_persona');
    }

    // NUEVA: Relación con Sede
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class, 'id_sede', 'id_sede');
    }
    public function getAuthIdentifier()
    {
        return $this->id_personal;
    }

    public $timestamps = true;
    protected $casts = [
        'es_vigente' => 'boolean',
    ];
}