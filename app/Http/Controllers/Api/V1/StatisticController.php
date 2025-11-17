<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\StatisticResource;
use App\Models\Statistic;
use Illuminate\Http\Request;

// 1. IMPORTAR AS HABILIDADES
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class StatisticController extends Controller
{
    // 2. USAR AS HABILIDADES
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Exibe todas as estatísticas. (PÚBLICO)
     */
    public function index()
    {
        $statistics = Statistic::orderBy('order', 'asc')->get();
        return StatisticResource::collection($statistics);
    }

    /**
     * Armazena uma nova estatística (Admin).
     */
    public function store(Request $request)
    {
        $this->authorize('config:gerenciar');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required|integer',
            'prefix' => 'nullable|string|max:10',
            'suffix' => 'nullable|string|max:10',
            'order' => 'nullable|integer',
        ]);

        $statistic = Statistic::create($validated);

        return (new StatisticResource($statistic))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Atualiza uma estatística (Admin).
     */
    public function update(Request $request, Statistic $statistic)
    {
        $this->authorize('config:gerenciar');

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'value' => 'sometimes|integer',
            'prefix' => 'sometimes|string|max:10',
            'suffix' => 'sometimes|string|max:10',
            'order' => 'sometimes|integer',
        ]);

        $statistic->update($validated);

        return new StatisticResource($statistic);
    }

    /**
     * Remove uma estatística (Admin).
     */
    public function destroy(Statistic $statistic)
    {
        $this->authorize('config:gerenciar');
        $statistic->delete();
        return response()->json(null, 204);
    }
}