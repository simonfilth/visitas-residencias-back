<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Torre;
use App\Models\Empresa;
use App\Models\Apartamento;
use Illuminate\Http\Request;

class ApartamentosController extends Controller
{
    public function guardar(Request $request)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $rules = [
            'nombre' => 'required',
            'torre_id' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
            $data['data'] = $validator->errors();
            return response()->json($data, $data['code']);
        }

        // $empresa = Empresa::findOrFail($id);

        $area = new Apartamento;
        $area->fill($request->all());
        $area->save();

        $data['status'] = 'success';

        return response()->json($data, $data['code']);
    }

    public function getAll($id)
    {
        // $torre = Torre::findOrFail($id);
        // $apartamentos = $torre->apartamentos;
        $apartamentos = Apartamento::join('torres as t', 't.id', 'apartamentos.torre_id')
            ->where('t.id', $id)
            ->select('apartamentos.*', 't.nombre as torre')
            ->get();

        $data = [
            'status' => 'success',
            'code' => 200,
            'data' => $apartamentos,
        ];

        return response()->json($data, $data['code']);
    }

    public function getAllByUser($id)
    {
        $usuario = User::findOrFail($id);
        $residencia = $usuario->residencia;
        $torres = $residencia->torres;
        $apartamentos = Apartamento::join('torres as t', 't.id', 'apartamentos.torre_id')
        ->join('residencias as r', 'r.id', 't.residencia_id')
        ->select('apartamentos.*', 't.nombre as torre')
        ->where('r.id', $residencia->id)->get();

        $data = [
            'status' => 'success',
            'code' => 200,
            'data' => $apartamentos,
        ];

        return response()->json($data, $data['code']);
    }

    public function get($id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $area = Area::findOrFail($id);

        unset($area->id);
        unset($area->created_at);
        unset($area->updated_at);
        unset($area->empresa_id);

        if($area){
            $data['status'] = 'success';
            $data['data'] = $area;
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
            'torre_id' => 'required',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
            $data['data'] = $validator->errors();
            return response()->json($data, $data['code']);
        }

        $area = Apartamento::findOrFail($id);
        $area->fill($request->all());
        $area->save();

        $data['status'] = 'success';
        // $data['data'] = $area->empresa_id;

        return response()->json($data, $data['code']);
    }

    public function eliminar($id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $apto = Apartamento::findOrFail($id);

        if($apto){
            if($apto->propietarios->count() > 0){
                foreach($apto->propietarios as $propietario){
                    $propietario->delete();
                }
            }
            $apto->delete();
            $data['status'] = 'success';
        }

        return response()->json($data, $data['code']);
    }

    public function cargarPropietarios($id)
    {
        $apartamento = Apartamento::findOrFail($id);
        $propietarios = $apartamento->propietarios;

        $data = [
            'status' => 'success',
            'code' => 200,
            'data' => $propietarios,
        ];

        return response()->json($data, $data['code']);
    }
}
