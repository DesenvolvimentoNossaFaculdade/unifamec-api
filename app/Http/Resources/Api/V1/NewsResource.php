<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'summary' => $this->summary,
            'content' => $this->content,
            'thumbnail' => $this->thumbnail_url,
            'isFeatured' => (bool) $this->is_featured,
            'publishedAt' => $this->published_at ? $this->published_at->toIso8601String() : null,
        ];
    }
}