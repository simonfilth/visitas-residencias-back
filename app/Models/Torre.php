<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Torre extends Model
{
    protected $table = 'torres';

    protected $fillable = [
        'residencia_id',
        'nombre',
    ];

    protected $hidden = [
        'pin',
    ];

    public function residencia()
    {
        return $this->belongsTo(Residencia::class, 'residencia_id');
    }

    public function apartamentos()
    {
        return $this->hasMany(Apartamento::class, 'torre_id', 'id');
    }

    public function agregar($request, $usuario)
    {
        $torre = new Torre;
        $torre->fill($request->all());
        if($usuario)
            $torre->residencia_id = $usuario;
        if($request->pin)
            $torre->pin = $request->pin;
        $torre->save();

        /* $file = $request->image;
        $filename = time(). '-' . $request->foto;
        $path = "empresas/".$torre->id;
        $torre->foto = $filename;
        \Storage::disk('public')->putFileAs($path, $file, $filename);

        $torre->save(); */

        return $torre;
    }

    public function actualizar($request)
    {
        $torre = $this;
        $torre->fill($request->all());
        if($request->pin)
            $torre->pin = $request->pin;

        /* if($request->image && $request->foto){
            $path = 'empresas/'.$torre->id;
            if ($torre->foto && \Storage::disk('public')->exists($path . '/' . $torre->foto)) {
                \Storage::disk('public')->delete($path . '/' . $torre->foto);
            }
            $file = $request->image;
            $filename = time(). '-' . $request->foto;

            $torre->foto = $filename;
            \Storage::disk('public')->putFileAs($path, $file, $filename);
        }
         */
        $torre->save();

        return $torre;
    }
}
