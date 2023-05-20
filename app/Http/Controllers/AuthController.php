<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            return $this->loginCredenciales($request);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'fail',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function loginCredenciales($request)
    {
        $validateUser = Validator::make($request->all(),
        [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validateUser->fails()){
            return response()->json([
                'status' => 'fail',
                'message' => 'Error de validación',
                'errors' => $validateUser->errors()
            ], 200);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = User::where('email', $request->email)->first();
        } else {
            return response()->json([
                'status' => 'fail',
                'message' => 'Correo o clave incorrecto',
            ], 200);
        }

        if($user){
            if($user->anulado == 1){
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Usted se encuentra inhabilitado',
                ], 200);
            }

            /* if ($user->tokens()->count() >= 4) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Se alcanzó el límite máximo de sesiones activas',
                ], 200);
            }
 */
            \Auth::login($user);

            /* if ($user->tokens()->count() >= 2) {
                $user->tokens()->orderBy('created_at', 'asc')->first()->delete();
            } */

            $token = $user->createToken("auth_token")->plainTextToken;

            return response()->json([
                'status' => 'success',
                'token' => $token,
                'user' => $user->load('tipoUsuario', 'residencia')
            ], 200);
        }
        else{
            return response()->json([
                'status' => 'no-registrado',
            ], 200);
        }
    }

    public function getUserAuth(Request $request)
    {
        $user = $request->user();

        return $user->load('tipoUsuario', 'residencia');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        /* auth()->user()->tokens()->delete(); */
        return response()->json(null);
    }
}
