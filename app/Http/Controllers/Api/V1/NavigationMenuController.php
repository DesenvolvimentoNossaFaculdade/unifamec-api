<?php

namespace App\Http\Controllers\Api\V1; // <-- CORRIGIDO

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NavigationLinkResource;
use App\Models\NavigationMenu; // <-- ESSA ERA A LINHA FALTANTE
use Illuminate\Http\Request;

// Importar Habilidades
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class NavigationMenuController extends Controller
{
    // Usar Habilidades
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Exibe a árvore de links de um menu (PÚBLICO).
     */
    public function show(string $slug)
    {
        $menu = NavigationMenu::where('slug', $slug)
                    ->with(['links.children', 'links.children.children'])
                    ->firstOrFail();
        
        return NavigationLinkResource::collection($menu->links);
    }

    /**
     * Armazena um novo menu (Admin).
     */
    public function store(Request $request)
    {
        $this->authorize('config:gerenciar');

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:navigation_menus',
        ]);

        $menu = NavigationMenu::create($validated);

        return response()->json($menu, 201);
    }

    /**
     * Atualiza um menu (Admin).
     */
    public function update(Request $request, NavigationMenu $navigationMenu)
    {
        $this->authorize('config:gerenciar');

        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'slug' => 'sometimes|string|max:100|unique:navigation_menus,slug,' . $navigationMenu->id,
        ]);

        $navigationMenu->update($validated);

        return response()->json($navigationMenu, 200);
    }

    /**
     * Remove um menu (Admin).
     */
    public function destroy(NavigationMenu $navigationMenu)
    {
        $this->authorize('config:gerenciar');
        $navigationMenu->delete();
        return response()->json(null, 204);
    }
}