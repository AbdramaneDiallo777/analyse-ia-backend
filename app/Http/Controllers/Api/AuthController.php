<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token,
        ]);
    }

    public function me(Request $request)
{
    return response()->json($request->user());
}

public function updateProfile(Request $request) // Vérifiez bien l'orthographe ici
{
    $user = $request->user();
    
    $validated = $request->validate([
        'name'       => 'sometimes|required|string|max:255',
        'email'      => 'sometimes|required|email|unique:users,email,' . $user->id,
        'phone'      => 'nullable|string|max:20',
        'job_title'  => 'nullable|string|max:255',
        'department' => 'nullable|string|max:255',
        'bio'        => 'nullable|string|max:1000',
    ]);

    $user->update($validated);

    return response()->json([
        'message' => 'Profil mis à jour avec succès',
        'user'    => $user
    ]);
}

public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required|current_password',
        'password'         => 'required|string|min:8|confirmed',
    ]);

    $request->user()->update([
        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
    ]);

    return response()->json(['message' => 'Mot de passe modifié avec succès']);
}

public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Déconnexion réussie']);
}

   
}

