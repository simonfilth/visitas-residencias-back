<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nombre', 'email', 'tipo_usuario_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tipoUsuario()
    {
        return $this->belongsTo(TipoUsuario::class, 'tipo_usuario_id');
    }

    public function residencia()
    {
        return $this->hasOne(Residencia::class, 'usuario_id', 'id');
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class, 'usuario_id', 'id');
    }

    public function agregar($request, $tipoUsuario)
    {
        $usuario = new User;
        $usuario->fill($request->all());
        $usuario->tipo_usuario_id = $tipoUsuario;
        $usuario->password = bcrypt($request->password);
        $usuario->save();

        return $usuario;
    }
}
