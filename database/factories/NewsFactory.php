<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(6);
        $slug = Str::slug($title);

        return [
            'title' => $title,
            'slug' => $slug,
            'summary' => fake()->paragraph(3),
            'content' => fake()->paragraph(30),
            'thumbnail_url' => 'https://via.placeholder.com/400x300.png?text=Noticia',
            'header_image_url' => 'https://via.placeholder.com/1200x400.png?text=Noticia',
            'is_featured' => fake()->boolean(25),
            'user_id' => null,
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}