<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\GalleryCategoryResource;
use App\Models\GalleryCategory;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class GalleryCategoryController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    // PÚBLICO: Pega os filtros
    public function index()
    {
        $categories = GalleryCategory::orderBy('order')->get();
        return GalleryCategoryResource::collection($categories);
    }

    // PRIVADO: Cria um filtro
    public function store(Request $request)
    {
        $this->authorize('galeria:gerenciar');
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:gallery_categories',
            'order' => 'nullable|integer',
        ]);
        $category = GalleryCategory::create($validated);
        return (new GalleryCategoryResource($category))->response()->setStatusCode(201);
    }

    // (Não precisamos de 'show' público, o index resolve)

    // PRIVADO: Atualiza um filtro
    public function update(Request $request, GalleryCategory $galleryCategory)
    {
        $this->authorize('galeria:gerenciar');
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'slug' => 'sometimes|string|max:100|unique:gallery_categories,slug,' . $galleryCategory->id,
            'order' => 'sometimes|integer',
        ]);
        $galleryCategory->update($validated);
        return new GalleryCategoryResource($galleryCategory);
    }

    // PRIVADO: Deleta um filtro
    public function destroy(GalleryCategory $galleryCategory)
    {
        $this->authorize('galeria:gerenciar');
        $galleryCategory->delete(); // Imagens em cascata
        return response()->json(null, 204);
    }
}