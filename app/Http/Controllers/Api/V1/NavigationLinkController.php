<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NavigationLinkResource;
use App\Models\NavigationLink;
use Illuminate\Http\Request;

// 1. IMPORTAR AS HABILIDADES
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class NavigationLinkController extends Controller
{
    // 2. USAR AS HABILIDADES
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Armazena um novo link (Admin).
     */
    public function store(Request $request)
    {
        $this->authorize('config:gerenciar');

        $validated = $request->validate([
            'navigation_menu_id' => 'required|exists:navigation_menus,id',
            'parent_id' => 'nullable|exists:navigation_links,id',
            'label' => 'required|string|max:100',
            'url' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'target_blank' => 'boolean',
        ]);

        $link = NavigationLink::create($validated);

        return (new NavigationLinkResource($link))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Atualiza um link (Admin).
     */
    public function update(Request $request, NavigationLink $navigationLink)
    {
        $this->authorize('config:gerenciar');

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:navigation_links,id',
            'label' => 'sometimes|string|max:100',
            'url' => 'sometimes|string|max:255',
            'order' => 'sometimes|integer',
            'target_blank' => 'sometimes|boolean',
        ]);

        $navigationLink->update($validated);

        return new NavigationLinkResource($navigationLink);
    }

    /**
     * Remove um link (Admin).
     */
    public function destroy(NavigationLink $navigationLink)
    {
        $this->authorize('config:gerenciar');
        $navigationLink->delete(); // Filhos serão deletados em cascata
        return response()->json(null, 204);
    }
}