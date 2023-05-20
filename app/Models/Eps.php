<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Eps extends Model
{
    protected $table = 'eps';

    protected $fillable = [
        'nombre'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public $timestamps = false;
}
