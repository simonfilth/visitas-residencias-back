<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Torre;
use App\Models\Propietario;
use Illuminate\Http\Request;

class PropietariosController extends Controller
{
    public function guardar(Request $request)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $rules = [
            'nombre' => 'required',
            'apartamento_id' => 'required',
            'email' => 'required|email|unique:propietarios',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
            $data['data'] = $validator->errors();
            return response()->json($data, $data['code']);
        }

        $empleado = new Propietario;
        $empleado->fill($request->all());
        $empleado->save();

        $data['status'] = 'success';

        return response()->json($data, $data['code']);
    }

    public function getAll($id)
    {
        $propietarios = Torre::where('torres.id', $id)
        ->join('apartamentos as a', 'torres.id', 'a.torre_id')
        ->join('propietarios as p', 'a.id', 'p.apartamento_id')
        ->select('p.*', 'a.nombre as apartamento', 'torres.nombre as torre', 'torres.id as torre_id')
        ->get();

        $data = [
            'status' => 'success',
            'code' => 200,
            'data' => $propietarios,
        ];

        return response()->json($data, $data['code']);
    }

    public function getAllByUser($id)
    {
        $usuario = User::findOrFail($id);
        $residencia = $usuario->residencia;
        $torres = $residencia->torres;
        $propietarios = Propietario::join('apartamentos as a', 'a.id', 'propietarios.apartamento_id')
        ->join('torres as t', 't.id', 'a.torre_id')
        ->join('residencias as r', 'r.id', 't.residencia_id')
        ->select('propietarios.*', 't.nombre as torre', 'a.nombre as apartamento', 't.id as torre_id')
        ->where('r.id', $residencia->id)->get();

        $data = [
            'status' => 'success',
            'code' => 200,
            'data' => $propietarios
        ];

        return response()->json($data, $data['code']);
    }

    public function get($id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $empleado = Propietario::findOrFail($id);

        unset($empleado->id);
        unset($empleado->created_at);
        unset($empleado->updated_at);

        if($empleado){
            $data['status'] = 'success';
            $data['data'] = $empleado->load('area');
        }

        return response()->json($data, $data['code']);
    }

    public function actualizar(Request $request, $id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $propietario = Propietario::findOrFail($id);

        $rules = [
            'nombre' => 'required',
            'apartamento_id' => 'required',
            'email' => "required|email|unique:propietarios,email,$propietario->id,id",
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
            $data['data'] = $validator->errors();
            return response()->json($data, $data['code']);
        }


        $propietario->fill($request->all());
        $propietario->save();

        $data['status'] = 'success';
        // $data['data'] = $empleado->area->empresa_id;

        return response()->json($data, $data['code']);
    }

    public function eliminar($id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $propietario = Propietario::findOrFail($id);

        if($propietario){
            $propietario->delete();
            $data['status'] = 'success';
        }

        return response()->json($data, $data['code']);
    }
}
