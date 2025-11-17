<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GalleryImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // O seu front-end (images) espera { id, url, alt, category, categoryLabel }
        return [
            'id' => $this->id,
            'url' => $this->url,
            'alt' => $this->alt,
            'category' => $this->whenLoaded('category', $this->category->slug), // "laboratorios"
            'categoryLabel' => $this->whenLoaded('category', $this->category->name), // "Laboratórios"
        ];
    }
}