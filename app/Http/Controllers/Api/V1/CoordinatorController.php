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
        // ****** A CORREÇÃO ESTÁ AQUI ******
        // Usamos o método 'role' do Spatie
        $coordinators = User::role('Coordenador')
                            ->orderBy('name', 'asc')
                            ->get();
                            
        return CoordinatorResource::collection($coordinators);
    }

    /**
     * Exibe um coordenador específico.
     */
    public function show(User $user)
    {
        // ****** A CORREÇÃO ESTÁ AQUI ******
        if (! $user->hasRole('Coordenador')) {
            abort(404);
        }
        
        return new CoordinatorResource($user);
    }
}