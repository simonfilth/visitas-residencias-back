<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoSangre extends Model
{
    protected $table = 'tipo_sangre';

    protected $fillable = [
        'nombre'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public $timestamps = false;
}
