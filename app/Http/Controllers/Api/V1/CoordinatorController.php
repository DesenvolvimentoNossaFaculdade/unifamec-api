<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CoordinatorResource; // Importe
use App\Models\User; // Importe
use Illuminate\Http\Request;

class CoordinatorController extends Controller
{
    /**
     * Exibe uma lista de coordenadores.
     */
    public function index()
    {
        $coordinators = User::where('role', 'coordinator')
                            ->orderBy('name', 'asc')
                            ->get();

        return CoordinatorResource::collection($coordinators);
    }

    /**
     * Exibe um coordenador específico.
     */
    public function show(User $user) // Vamos buscar pelo ID
    {
        // Garante que só podemos "ver" um coordenador, não um usuário comum
        if ($user->role !== 'coordinator') {
            abort(404);
        }

        return new CoordinatorResource($user);
    }
}