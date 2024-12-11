<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request) {
        $data = $request->validated();

        // Revisar el password
        if (!Auth::attempt($data)) {
            return response()->json([
                'success' => false,
                'message' => 'El email o el password son incorrectos',
                'errors' => ['El email o el password son incorrectos']
            ], 422);            
        }        

        // Autenticar al usuario
        $user = Auth::user();
        
        return [
            'user' => $user
        ];
    }

    public function logout(Request $request)
    {
        $user = $request->user(); // Obtén el usuario autenticado

        // Revoca el token actual del usuario
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada con éxito'], 200);
    }
}

