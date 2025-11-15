<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'thumbnail' => $this->thumbnail_url,
            'isFeatured' => $this->is_featured,
            'publishedAt' => $this->published_at->format('d/m/Y'),
            'content' => $this->when(request()->routeIs('news.show'), $this->content),
            'headerImage' => $this->when(request()->routeIs('news.show'), $this->header_image_url),
        ];
    }
}