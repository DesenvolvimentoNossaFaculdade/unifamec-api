<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ExamLocationResource;
use App\Models\ExamLocation;
use App\Http\Requests\Api\V1\StoreExamLocationRequest; // Importando o Request dedicado
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;

class ExamLocationController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * PÚBLICO: Retorna apenas os locais ativos para o site/landing page.
     */
    public function index()
    {
        // Mantive sua lógica: Index é público e só vê ativos
        $locations = ExamLocation::where('is_active', true)
            ->orderBy('date', 'asc')
            ->get();
            
        return ExamLocationResource::collection($locations);
    }

    /**
     * ADMIN: Retorna tudo (ativos e inativos) para o Dashboard.
     */
    public function all()
    {
        // CORREÇÃO: O nome da permissão deve bater com o Seeder ('locais-prova:gerenciar')
        $this->authorize('locais-prova:gerenciar');
        
        $locations = ExamLocation::orderBy('created_at', 'desc')->get();
        
        return ExamLocationResource::collection($locations);
    }

    /**
     * ADMIN: Cria um novo local.
     */
    public function store(StoreExamLocationRequest $request)
    {
        // CORREÇÃO: Nome da permissão
        $this->authorize('locais-prova:gerenciar');

        // O $request->validated() já retorna os dados limpos e validados
        $location = ExamLocation::create($request->validated());

        return (new ExamLocationResource($location))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * ADMIN: Atualiza um local.
     */
    public function update(StoreExamLocationRequest $request, ExamLocation $examLocation)
    {
        // CORREÇÃO: Nome da permissão
        $this->authorize('locais-prova:gerenciar');

        $examLocation->update($request->validated());

        return new ExamLocationResource($examLocation);
    }

    /**
     * ADMIN: Remove um local.
     */
    public function destroy(ExamLocation $examLocation): JsonResponse
    {
        // CORREÇÃO: Nome da permissão
        $this->authorize('locais-prova:gerenciar');
        
        $examLocation->delete();

        return response()->json(null, 204);
    }
}