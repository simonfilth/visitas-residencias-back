<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Residencia extends Model
{
    protected $table = 'residencias';

    protected $fillable = [
        'usuario_id',
        'nombre_residencia',
        'correo',
        'nit',
    ];

    protected $hidden = [
        'pin',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function torres()
    {
        return $this->hasMany(Torre::class, 'residencia_id', 'id');
    }

    public function agregar($request, $usuario)
    {
        $residencia = new Residencia;
        $residencia->fill($request->all());
        $residencia->usuario_id = $usuario;
        $residencia->pin = $request->pin;
        $residencia->save();

        $file = $request->image;
        $filename = time(). '-' . $request->foto;
        $path = "residencias/";
        $residencia->foto = $filename;
        \Storage::disk('public')->putFileAs($path, $file, $filename);

        $residencia->save();

        return $residencia;
    }

    public function actualizar($request)
    {
        $residencia = $this;
        $residencia->fill($request->all());
        if($request->pin)
            $residencia->pin = $request->pin;

        if($request->image && $request->foto){
            $path = "residencias/";
            if ($residencia->foto && \Storage::disk('public')->exists($path. '/' . $residencia->foto)) {
                \Storage::disk('public')->delete($path. '/' . $residencia->foto);
            }
            $file = $request->image;
            $filename = time(). '-' . $request->foto;

            $residencia->foto = $filename;
            \Storage::disk('public')->putFileAs($path, $file, $filename);
        }

        $residencia->save();

        return $residencia;
    }
}
