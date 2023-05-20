<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Edificio;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function anular($id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $usuario = User::findOrFail($id);

        if($usuario){
            $usuario->anulado = $usuario->anulado == 0 ? 1 : 0;
            $usuario->save();

            $data['status'] = 'success';
            $data['mensaje'] = $usuario->anulado == 1 ? "Inhabilitado correctamente" : "Habilitado correctamente";
        }

        return response()->json($data, $data['code']);
    }

    public function verificarPin(Request $request, $id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $rules = [
            'pin' => 'required|digits:4',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
            $data['data'] = $validator->errors();
            return response()->json($data, $data['code']);
        }
        $item = null;
        $usuario = User::findOrFail($id);


        $item = $usuario->residencia;

        $data['usuario'] = $usuario;
        $data['item'] = $item;
        $data['tipoUsuario'] = $request->tipoUsuario;

        if($item && $item->pin == $request->pin){
            $data['status'] = 'success';
        }

        return response()->json($data, $data['code']);
    }
}
