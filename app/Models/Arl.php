<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arl extends Model
{
    protected $table = 'arl';

    protected $fillable = [
        'nombre'
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public $timestamps = false;
}
