<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NavigationLinkResource; // Importe
use App\Models\NavigationMenu; // Importe
use Illuminate\Http\Request;

class NavigationMenuController extends Controller
{
    /**
     * Exibe a árvore de links de um menu específico pelo slug.
     */
    public function show(string $slug)
    {
        // Eager load (carregamento ansioso) dos filhos, e filhos dos filhos...
        $menu = NavigationMenu::where('slug', $slug)
                    ->with(['links.children', 'links.children.children']) // Carrega 3 níveis
                    ->firstOrFail();

        // Retorna uma coleção do Resource de Links
        return NavigationLinkResource::collection($menu->links);
    }
}