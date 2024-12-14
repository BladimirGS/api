<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request) {
        $data = $request->validated();

        if (!Auth::attempt($data)) {
            return response()->json([
                'success' => false,
                'message' => 'El email o el password son incorrectos',
                'errors' => ['El email o el password son incorrectos']
            ], 422);            
        }        

        $user = Auth::user();
        
        return [
            'user' => $user
        ];
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['message' => 'Sesión cerrada con éxito'], 200);
    }    
}

