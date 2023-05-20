<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Propietario extends Model
{
    use Notifiable;

    protected $table = 'propietarios';

    protected $fillable = [
        'nombre',
        'email',
        'apartamento_id',
    ];

    public function apartamento()
    {
        return $this->belongsTo(Apartamento::class, 'apartamento_id');
    }

}
