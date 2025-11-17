<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\GalleryImageResource;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

class GalleryImageController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    // PÚBLICO: Pega as imagens (o front filtra)
    public function index()
    {
        $images = GalleryImage::with('category')->orderBy('order')->get();
        return GalleryImageResource::collection($images);
    }

    // PRIVADO: Adiciona uma foto
    public function store(Request $request)
    {
        $this->authorize('galeria:gerenciar');
        $validated = $request->validate([
            'gallery_category_id' => 'required|exists:gallery_categories,id',
            'url' => 'required|string', // (Futuramente, upload)
            'alt' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);
        $image = GalleryImage::create($validated);
        return (new GalleryImageResource($image))->response()->setStatusCode(201);
    }

    // PRIVADO: Atualiza uma foto
    public function update(Request $request, GalleryImage $galleryImage)
    {
        $this->authorize('galeria:gerenciar');
        $validated = $request->validate([
            'gallery_category_id' => 'sometimes|exists:gallery_categories,id',
            'url' => 'sometimes|string',
            'alt' => 'sometimes|string|max:255',
            'order' => 'sometimes|integer',
        ]);
        $galleryImage->update($validated);
        return new GalleryImageResource($galleryImage);
    }

    // PRIVADO: Deleta uma foto
    public function destroy(GalleryImage $galleryImage)
    {
        $this->authorize('galeria:gerenciar');
        $galleryImage->delete();
        return response()->json(null, 204);
    }
}