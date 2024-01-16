<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use App\Models\Visita;
use App\Models\Empresa;
use App\Models\Empleado;
use App\Models\DatosExport;
use App\Models\Propietario;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\RegistroVisita;

class VisitasController extends Controller
{
    public function guardar(Request $request, $id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $rules = [
            'torre' => 'required',
            'apartamento' => 'required',
            'propietario' => 'required',
            'cedula' => 'required',
            /* 'arl_id' => 'required',
            'tipo_sangre_id' => 'required',
            'eps_id' => 'required', */
            'visitante_nombre' => 'required',
            'image' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
            $data['data'] = $validator->errors();
            return response()->json($data, $data['code']);
        }

        $usuario = User::findOrFail($id);

        $visita = new Visita;

        $visita->usuario_id = $id;
        $visita->fill($request->all());
        $visita->hora_ingreso = now();
        $visita->visitante_foto = time();
        $visita->save();

        $file = $request->image;
        $filename = time(). '-' . Str::slug($request->visitante_nombre) . ".jpg";
        $path = "visitas";
        $visita->visitante_foto = $filename;

        \Storage::disk('public')->putFileAs($path, $file, $filename);

        $visita->save();

        $data['status'] = 'success';

        $propietario = Propietario::findOrFail($request->propietario_id);

        $propietario->notify(new RegistroVisita($propietario, $visita));

        return response()->json($data, $data['code']);
    }

    public function getByResidencia($id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $usuario = User::findOrFail($id);

         $visitas = Visita::
        where('usuario_id', $usuario->id)
        ->orderBy('visitas.hora_ingreso', 'desc')
        ->get();

        if($visitas){
            $data['status'] = 'success';
            $data['data'] = $visitas->load('usuario.residencia', 'arl', 'eps', 'tipoSangre');
        }

        return response()->json($data, $data['code']);
    }

    public function testCorreo()
    {
        $visita = Visita::first();
        $propietario = Propietario::findOrFail(1);


        $propietario->notify(new RegistroVisita($propietario, $visita));

        return "enviado";
    }


    public function exportarExcel($id)
    {
        $visitas = Visita::
        join('users as u', 'u.id', 'visitas.usuario_id')
        /* ->join('arl', 'arl.id', 'visitas.arl_id')
        ->join('eps', 'eps.id', 'visitas.eps_id')
        ->join('tipo_sangre', 'tipo_sangre.id', 'visitas.tipo_sangre_id') */
        ->where('u.id', $id)
        ->select(
            'visitas.id',
            'visitas.visitante_nombre',
            'visitas.cedula',
            /* 'arl.nombre as nombre_arl',
            'eps.nombre as nombre_eps',
            'tipo_sangre.nombre as nombre_tipo_sangre', */
            'visitas.torre',
            'visitas.apartamento',
            'visitas.propietario',
            'visitas.visitante_foto',
            'visitas.hora_ingreso',
            'visitas.observacion',
        )
        ->get();

        return Excel::download(new DatosExport($visitas), 'datos.xlsx');
    }

    public function eliminar($id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $visita = Visita::findOrFail($id);

        if($visita){
            if ($visita->visitante_foto && \Storage::disk('public')->exists("visitas/" . $visita->visitante_foto)) {
                \Storage::disk('public')->delete("visitas/". $visita->visitante_foto);
            }
            $visita->delete();
            $data['status'] = 'success';
        }

        return response()->json($data, $data['code']);
    }
}
