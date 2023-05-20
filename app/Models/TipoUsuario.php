<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    protected $table = 'tipos_usuarios';

    protected $fillable = [
        'nombre'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public $timestamps = false;
}
