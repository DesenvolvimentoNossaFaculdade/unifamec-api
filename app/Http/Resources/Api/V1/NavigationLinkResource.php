<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NavigationLinkResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'url' => $this->url,
            'targetBlank' => $this->target_blank,

            // Se a relação 'children' foi carregada e não está vazia,
            // chama este mesmo resource para formatar os filhos.
            'children' => NavigationLinkResource::collection($this->whenLoaded('children')),
        ];
    }
}