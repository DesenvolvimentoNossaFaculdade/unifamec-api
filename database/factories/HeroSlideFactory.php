<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class HeroSlideFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'subtitle' => fake()->sentence(10),
            'button_text' => 'Conheça Nossos Cursos',
            'button_link' => '/cursos',
            'image_url_desktop' => 'https://via.placeholder.com/1920x1080.png?text=Hero+Desktop',
            'image_url_mobile' => 'https://via.placeholder.com/800x1200.png?text=Hero+Mobile',
            'order' => 0,
            'is_active' => true,
        ];
    }
}