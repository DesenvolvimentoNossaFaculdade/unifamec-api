<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeroSlideResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'buttonText' => $this->button_text,
            'buttonLink' => $this->button_link,
            'imageUrlDesktop' => $this->image_url_desktop,
            'imageUrlMobile' => $this->image_url_mobile ?? $this->image_url_desktop,
        ];
    }
}