<?php

namespace App\Http\Controllers;

use App\Models\Arl;
use App\Models\Eps;
use App\Models\User;
use App\Models\Torre;
use App\Models\Residencia;
use App\Models\TipoSangre;
use Illuminate\Http\Request;

class ResidenciasController extends Controller
{
    public function guardar(Request $request)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        if(\Auth::user()->tipoUsuario->nombre != 'Administrador'){
            $data['error'] = "No eres administrador";
            return response()->json($data, $data['code']);
        }
        // return response()->json($data, $data['code']);
        $rules = [
            'nombre' => 'required',
            'email' => 'required|email|unique:users|unique:propietarios',
            'password' => 'required|min:6',
            'nombre_residencia' => 'required',
            'nit' => 'required',
            'pin' => 'required|digits:4',
            'foto' => 'required',
            // 'image' => 'required|image',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
            $data['data'] = $validator->errors();
            return response()->json($data, $data['code']);
        }

        $usuario = new User;
        $usuario = $usuario->agregar($request, 2);
        $residencia = new Residencia;
        $residencia = $residencia->agregar($request, $usuario->id);

        $data['status'] = 'success';

        return response()->json($data, $data['code']);
    }

    public function getAll()
    {
        $residencias = Residencia::get();

        $data = [
            'status' => 'success',
            'code' => 200,
            'residencias' => $residencias->load('usuario'),
        ];

        return response()->json($data, $data['code']);
    }

    public function get($id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $residencia = Residencia::findOrFail($id);
        $usuario = $residencia->usuario;

        $residencia->nombre = $usuario->nombre;
        $residencia->email = $usuario->email;
        $residencia->password = null;
        $residencia->imagen = null;

        unset($residencia->id);
        unset($residencia->created_at);
        unset($residencia->updated_at);
        unset($residencia->usuario_id);
        unset($residencia->usuario);

        if($residencia){
            $data['status'] = 'success';
            $data['data'] = $residencia;
        }

        return response()->json($data, $data['code']);
    }

    public function actualizar(Request $request, $id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $residencia = Residencia::findOrFail($id);
        $usuario = $residencia->usuario;

        $rules = [
            'nombre' => 'required',
            'email' => "required|email|unique:users,email,$usuario->id,id|unique:propietarios",
            'password' => 'nullable|min:6',
            'nombre_residencia' => 'required',
            'nit' => 'required',
            'pin' => 'nullable|digits:4',
            'foto' => 'nullable',
            'image' => 'nullable',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
            $data['data'] = $validator->errors();
            return response()->json($data, $data['code']);
        }

        $usuario->fill($request->all());
        if($request->password)
            $usuario->password = bcrypt($request->password);
        $usuario->save();

        $residencia->actualizar($request);

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
            $residencia = $usuario->residencia;
            $torres = $residencia->torres;

            $arl = Arl::get();
            $eps = Eps::get();
            $tipoSangre = TipoSangre::get();

            $data['status'] = 'success';
            $data['arl'] = $arl;
            $data['eps'] = $eps;
            $data['tipoSangre'] = $tipoSangre;
            $data['residencia']  = $residencia;
            $data['torres']  = $torres;
        }

        return response()->json($data, $data['code']);
    }

    public function eliminar($id)
    {
        $data = [
            'status' => 'fail',
            'code' => 200,
        ];

        $residencia = Residencia::findOrFail($id);
        $usuario = $residencia->usuario;

        if($residencia){
            if($residencia->torres->count() > 0){

                foreach($residencia->torres as $torre){
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
                }
            }

            if ($residencia->foto && \Storage::disk('public')->exists('/residencias/' . $residencia->foto)) {
                \Storage::disk('public')->delete('/residencias/' . $residencia->foto);
            }
            $residencia->delete();

            if($usuario->visitas->count() > 0){
                foreach($usuario->visitas as $visita){
                    if ($visita->visitante_foto && \Storage::disk('public')->exists('/visitas/' . $visita->visitante_foto)) {
                        \Storage::disk('public')->delete('/visitas/' . $visita->visitante_foto);
                    }
                    $visita->delete();
                }
            }
            $usuario->delete();
            $data['status'] = 'success';
        }

        return response()->json($data, $data['code']);
    }
}
