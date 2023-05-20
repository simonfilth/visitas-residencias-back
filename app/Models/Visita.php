<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
    protected $table = 'visitas';

    protected $fillable = [
        'torre',
        'propietario',
        'apartamento',
        'usuario_id',
        'arl_id',
        'tipo_sangre_id',
        'eps_id',
        'visitante_nombre',
        'observacion',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function arl()
    {
        return $this->belongsTo(Arl::class, 'arl_id');
    }

    public function tipoSangre()
    {
        return $this->belongsTo(TipoSangre::class, 'tipo_sangre_id');
    }

    public function eps()
    {
        return $this->belongsTo(Eps::class, 'eps_id');
    }
}
