<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DocumentFactory extends Factory
{
    public function definition(): array
    {
        $title = 'Documento Exemplo ' . fake()->word();
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'file_url' => '/uploads/exemplo.pdf',
            'category' => fake()->randomElement(['manual', 'calendario', 'documento']),
            'is_active' => true,
        ];
    }
}