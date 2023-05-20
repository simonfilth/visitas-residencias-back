<?php

namespace App\Http\Controllers;

use App\Models\Arl;
use App\Models\Eps;
use App\Models\User;
use App\Models\Torre;
use App\Models\Residencia;
use App\Models\TipoSangre;
use App\Models\Apartamento;
use Illuminate\Http\Request;

class TorresController extends Controller
{
    public function guardar(Request $request, $id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $rules = [
            'nombre' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
            $data['data'] = $validator->errors();
            return response()->json($data, $data['code']);
        }

        $residencia = Residencia::findOrFail($id);

        $torre = new Torre;
        $torre->residencia_id = $residencia->id;
        $torre->fill($request->all());
        $torre->save();

        $data['status'] = 'success';

        return response()->json($data, $data['code']);
    }

    public function getAll($id)
    {
        $usuario = User::findOrFail($id);
        $residencia = $usuario->residencia;
        $torres = $residencia->torres;
        $apartamentos = Apartamento::join('torres as t', 't.id', 'apartamentos.torre_id')
        ->join('residencias as r', 'r.id', 't.residencia_id')
        ->where('r.id', $residencia->id)->count();

        $data = [
            'status' => 'success',
            'code' => 200,
            'data' => $torres->load('residencia.usuario'),
            'countApartamentos' => $apartamentos,
        ];

        return response()->json($data, $data['code']);
    }

    public function get($id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $torre = Torre::findOrFail($id);

        unset($torre->created_at);
        unset($torre->updated_at);

        if($torre){
            $data['status'] = 'success';
            $data['data'] = $torre;
        }

        return response()->json($data, $data['code']);
    }

    public function actualizar(Request $request, $id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $rules = [
            'nombre' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
            $data['data'] = $validator->errors();
            return response()->json($data, $data['code']);
        }

        $torre = Torre::findOrFail($id);
        $torre->fill($request->all());
        $torre->save();

        $data['status'] = 'success';

        return response()->json($data, $data['code']);
    }

    public function cargarDatos($id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $usuario = User::findOrFail($id);


        if($usuario){
            $torre = $usuario->empresa;

            $arl = Arl::get();
            $eps = Eps::get();
            $tipoSangre = TipoSangre::get();
            $departamentos = $torre->areas;

            $data['status'] = 'success';
            $data['arl'] = $arl;
            $data['eps'] = $eps;
            $data['tipoSangre'] = $tipoSangre;
            $data['departamentos'] = $departamentos;
            $data['empresa']  = $torre;
        }

        return response()->json($data, $data['code']);
    }

    /* public function cargarAreas($id)
    {
        $torre = Empresa::findOrFail($id);
        $areas = $torre->areas;

        $data = [
            'status' => 'success',
            'code' => 200,
            'areas' => $areas,
        ];

        return response()->json($data, $data['code']);
    } */

    public function eliminar($id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $torre = Torre::findOrFail($id);

        if($torre){
            if($torre->apartamentos->count() > 0){
                foreach($torre->apartamentos as $apto){
                    if($apto->propietarios->count() > 0){
                        foreach($apto->propietarios as $propietario){
                            $propietario->delete();
                        }
                    }
                    $apto->delete();
                }
            }
            $torre->delete();
            $data['status'] = 'success';
        }

        return response()->json($data, $data['code']);
    }
}
