<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GalleryCategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // O seu front-end (categories) espera { slug, name }
        return [
            'slug' => $this->slug,
            'name' => $this->name,
        ];
    }
}