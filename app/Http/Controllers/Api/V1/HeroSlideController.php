<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\HeroSlideResource;
use App\Models\HeroSlide;
use Illuminate\Http\Request;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;

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
     * Armazena um novo slide (Marketing).
     */
    public function store(Request $request)
    {
        // ATUALIZADO para 'hero:gerenciar'
        $this->authorize('hero:gerenciar');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string',
            'button_text' => 'nullable|string',
            'button_link' => 'nullable|string',
            'image_url_desktop' => 'required|string',
            'image_url_mobile' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $slide = HeroSlide::create($validated);

        return (new HeroSlideResource($slide))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Atualiza um slide (Marketing).
     */
    public function update(Request $request, HeroSlide $heroSlide)
    {
        // ATUALIZADO para 'hero:gerenciar'
        $this->authorize('hero:gerenciar');

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'subtitle' => 'sometimes|string',
            'button_text' => 'sometimes|string',
            'button_link' => 'sometimes|string',
            'image_url_desktop' => 'sometimes|string',
            'image_url_mobile' => 'sometimes|string',
            'order' => 'sometimes|integer',
            'is_active' => 'sometimes|boolean',
        ]);

        $heroSlide->update($validated);

        return new HeroSlideResource($heroSlide);
    }

    /**
     * Remove um slide (Marketing).
     */
    public function destroy(HeroSlide $heroSlide)
    {
        // ATUALIZADO para 'hero:gerenciar'
        $this->authorize('hero:gerenciar');
        $heroSlide->delete();
        return response()->json(null, 204);
    }
}