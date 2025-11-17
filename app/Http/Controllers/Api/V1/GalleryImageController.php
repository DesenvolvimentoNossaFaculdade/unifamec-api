<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\GalleryImageResource;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage; // 1. IMPORTAR O STORAGE

class GalleryImageController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Exibe as imagens da galeria (PÚBLICO).
     */
    public function index()
    {
        $images = GalleryImage::with('category')->orderBy('order')->get();
        return GalleryImageResource::collection($images);
    }

    /**
     * Adiciona uma nova foto (Marketing) - AGORA COM UPLOAD
     */
    public function store(Request $request)
    {
        $this->authorize('galeria:gerenciar');

        // 2. VALIDAÇÃO ATUALIZADA
        $validated = $request->validate([
            'gallery_category_id' => 'required|exists:gallery_categories,id',
            'alt' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'image_file' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // url -> image_file
        ]);

        $imageUrl = null;

        // 3. LÓGICA DE UPLOAD
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('gallery', 'public');
            $imageUrl = Storage::url($path);
        }

        // 4. Criação
        $image = GalleryImage::create([
            'gallery_category_id' => $validated['gallery_category_id'],
            'alt' => $validated['alt'],
            'order' => $validated['order'] ?? 0,
            'url' => $imageUrl, // Salva a URL gerada
        ]);

        return (new GalleryImageResource($image))->response()->setStatusCode(201);
    }

    /**
     * Atualiza uma foto (Marketing) - AGORA COM UPLOAD
     */
    public function update(Request $request, GalleryImage $galleryImage)
    {
        $this->authorize('galeria:gerenciar');

        $validated = $request->validate([
            'gallery_category_id' => 'sometimes|exists:gallery_categories,id',
            'alt' => 'sometimes|string|max:255',
            'order' => 'sometimes|integer',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'clear_image' => 'nullable|boolean',
        ]);
        
        $updateData = $request->only(['gallery_category_id', 'alt', 'order']);

        // Lógica de Upload
        if ($request->hasFile('image_file')) {
            // Apaga a imagem antiga
            if ($galleryImage->url) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $galleryImage->url)); }
            
            // Salva a nova imagem
            $path = $request->file('image_file')->store('gallery', 'public');
            $updateData['url'] = Storage::url($path);
            
        } elseif ($request->input('clear_image') == true) {
             if ($galleryImage->url) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $galleryImage->url)); }
            $updateData['url'] = null;
        }

        $galleryImage->update($updateData);

        return new GalleryImageResource($galleryImage);
    }

    /**
     * Remove uma foto (Marketing).
     */
    public function destroy(GalleryImage $galleryImage)
    {
        $this->authorize('galeria:gerenciar');

        // Apaga a imagem do storage
        if ($galleryImage->url) {
            Storage::disk('public')->delete(str_replace(Storage::url(''), '', $galleryImage->url));
        }

        $galleryImage->delete();
        return response()->json(null, 204);
    }
}