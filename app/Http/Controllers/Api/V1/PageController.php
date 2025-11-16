<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PageResource; // Importe
use App\Models\Page; // Importe
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Lista todas as páginas (para admin).
     */
    public function index()
    {
        $pages = Page::orderBy('title')->get();
        return PageResource::collection($pages);
    }

    /**
     * Exibe uma página específica pelo Slug.
     */
    public function show(string $slug) // <-- Mudei de $idOrSlug para $slug
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return new PageResource($page);
    }

    // ... store, update, destroy (para o admin no futuro) ...
}