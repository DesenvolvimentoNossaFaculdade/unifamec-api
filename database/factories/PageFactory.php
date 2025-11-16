<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PageFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(3);
        $slug = Str::slug($title);

        return [
            'title' => $title,
            'slug' => $slug,
            'summary' => fake()->paragraph(2),
            'content' => fake()->paragraphs(15, true),
            'header_image_url' => 'https://via.placeholder.com/1200x300.png?text=' . urlencode($title),
        ];
    }
}