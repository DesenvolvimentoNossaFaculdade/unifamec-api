<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Autentica o usuário e retorna um token.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        $user->tokens()->delete();
        $token = $user->createToken('api-token')->plainTextToken;

        // ****** A CORREÇÃO ESTÁ AQUI ******
        // Em vez de 'role', pegamos os papéis do Spatie
        return response()->json([
            'message' => 'Login bem-sucedido!',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(), // <-- MUDANÇA
            ],
        ]);
    }

    /**
     *  Faz logout (revoga o token) do usuário autenticado.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout bem-sucedido.'
        ], 200);
    }
}