<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     *  convert into array
     * 
     *  @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'modality' => $this->modality,
            'duration' => $this->duration_semesters,
            'price' => $this->price,
            'thumbnail' => $this->thumbnail_url,
            'isFeatured' => $this->is_featured,
            'content' => $this->when(request()->routeIs('courses.show'), $this->content),
            'headerImage' => $this->when(request()->routeIs('courses.show'), $this->header_image_url),
        ];
    }
}