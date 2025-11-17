<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;

// 1. IMPORTAR AS HABILIDADES
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class PageController extends Controller
{
    // 2. USAR AS HABILIDADES
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Lista todas as páginas (PÚBLICO, mas talvez Admin no futuro).
     */
    public function index()
    {
        $pages = Page::orderBy('title')->get();
        return PageResource::collection($pages);
    }

    /**
     * Exibe uma página específica pelo Slug (PÚBLICO).
     */
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return new PageResource($page);
    }

    /**
     * Armazena uma nova página (Admin).
     */
    public function store(Request $request)
    {
        $this->authorize('paginas:gerenciar');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages',
            'summary' => 'nullable|string',
            'content' => 'required|string',
            'header_image_url' => 'nullable|string',
        ]);

        $page = Page::create($validated);

        return (new PageResource($page))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Atualiza uma página (Admin).
     */
    public function update(Request $request, Page $page)
    {
        $this->authorize('paginas:gerenciar');

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:pages,slug,' . $page->id,
            'summary' => 'sometimes|string',
            'content' => 'sometimes|string',
            'header_image_url' => 'sometimes|string',
        ]);

        $page->update($validated);

        return new PageResource($page);
    }

    /**
     * Remove uma página (Admin).
     */
    public function destroy(Page $page)
    {
        $this->authorize('paginas:gerenciar');
        $page->delete();
        return response()->json(null, 204);
    }
}