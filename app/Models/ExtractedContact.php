<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtractedContact extends Model
{
    protected $fillable = [
        'user_id',
        'instance',
        'wa_id',
        'phone',
        'is_lid',
        'name',
        'source_type',
        'source_ref',
    ];
}