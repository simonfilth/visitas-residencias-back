<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apartamento extends Model
{
    protected $table = 'apartamentos';

    protected $fillable = [
        'nombre',
        'torre_id',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function torre()
    {
        return $this->belongsTo(Torre::class, 'torre_id');
    }

    public function propietarios()
    {
        return $this->hasMany(Propietario::class, 'apartamento_id', 'id');
    }
}
