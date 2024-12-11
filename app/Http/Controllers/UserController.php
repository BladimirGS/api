<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pokemons = User::all()->map(function ($pokemon) {
            return [
                'id' => $pokemon->id,
                'name' => $pokemon->name,
                'email' => $pokemon->email,
            ];
        });

        return response()->json($pokemons);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ]);
    
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);  // Asegúrate de cifrar la contraseña
        $user->save();
    
        return response()->json($user, 201);
    }    

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'nullable',
        ]);
    
        $user->name = $request->input('name');
        $user->email = $request->input('email');
    
        // Solo actualizar la contraseña si se proporciona
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }
    
        $user->save();
    
        return response()->json([
            'status' => 'success',
            'user' => $user,
        ], 200);
    }    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pokemon = User::find($id);

        if (!$pokemon) {
            return response()->json(['message' => 'Pokémon not found'], 404);
        }
        $pokemon->delete();

        return response()->json(['message' => 'Pokémon deleted successfully']);
    }
}
