<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\HeroSlideResource;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Storage; // 1. IMPORTAR O STORAGE

class HeroSlideController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Exibe os slides ativos do hero. (PÚBLICO)
     */
    public function index()
    {
        $slides = HeroSlide::where('is_active', true)
                           ->orderBy('order', 'asc')
                           ->get();
                           
        return HeroSlideResource::collection($slides);
    }

    /**
     * Armazena um novo slide (Marketing) - AGORA COM UPLOAD
     */
    public function store(Request $request)
    {
        $this->authorize('hero:gerenciar');

        // 2. VALIDAÇÃO ATUALIZADA
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'button_text' => 'nullable|string',
            'button_link' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',

            // Validação de arquivos
            'image_url_desktop_file' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url_mobile_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $desktopUrl = null;
        $mobileUrl = null;

        // 3. LÓGICA DE UPLOAD
        if ($request->hasFile('image_url_desktop_file')) {
            $path = $request->file('image_url_desktop_file')->store('hero', 'public');
            $desktopUrl = Storage::url($path);
        }
        if ($request->hasFile('image_url_mobile_file')) {
            $path = $request->file('image_url_mobile_file')->store('hero', 'public');
            $mobileUrl = Storage::url($path);
        }

        // 4. Criação
        $slide = HeroSlide::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'button_text' => $validated['button_text'] ?? null,
            'button_link' => $validated['button_link'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
            'image_url_desktop' => $desktopUrl,
            'image_url_mobile' => $mobileUrl,
        ]);

        return (new HeroSlideResource($slide))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Atualiza um slide (Marketing) - AGORA COM UPLOAD
     */
    public function update(Request $request, HeroSlide $heroSlide)
    {
        $this->authorize('hero:gerenciar');

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'subtitle' => 'sometimes|string',
            'button_text' => 'sometimes|string',
            'button_link' => 'sometimes|string',
            'order' => 'sometimes|integer',
            'is_active' => 'sometimes|boolean',
            
            'image_url_desktop_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_url_mobile_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'clear_desktop' => 'nullable|boolean',
            'clear_mobile' => 'nullable|boolean',
        ]);
        
        $updateData = $request->only(['title', 'subtitle', 'button_text', 'button_link', 'order', 'is_active']);

        // Lógica de Upload Desktop
        if ($request->hasFile('image_url_desktop_file')) {
            if ($heroSlide->image_url_desktop) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $heroSlide->image_url_desktop)); }
            $path = $request->file('image_url_desktop_file')->store('hero', 'public');
            $updateData['image_url_desktop'] = Storage::url($path);
        } elseif ($request->input('clear_desktop') == true) {
            if ($heroSlide->image_url_desktop) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $heroSlide->image_url_desktop)); }
            $updateData['image_url_desktop'] = null;
        }

        // Lógica de Upload Mobile
        if ($request->hasFile('image_url_mobile_file')) {
            if ($heroSlide->image_url_mobile) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $heroSlide->image_url_mobile)); }
            $path = $request->file('image_url_mobile_file')->store('hero', 'public');
            $updateData['image_url_mobile'] = Storage::url($path);
        } elseif ($request->input('clear_mobile') == true) {
            if ($heroSlide->image_url_mobile) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $heroSlide->image_url_mobile)); }
            $updateData['image_url_mobile'] = null;
        }

        $heroSlide->update($updateData);

        return new HeroSlideResource($heroSlide);
    }

    /**
     * Remove um slide (Marketing).
     */
    public function destroy(HeroSlide $heroSlide)
    {
        $this->authorize('hero:gerenciar');

        // Apaga as imagens junto
        if ($heroSlide->image_url_desktop) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $heroSlide->image_url_desktop)); }
        if ($heroSlide->image_url_mobile) { Storage::disk('public')->delete(str_replace(Storage::url(''), '', $heroSlide->image_url_mobile)); }

        $heroSlide->delete();
        return response()->json(null, 204);
    }
}