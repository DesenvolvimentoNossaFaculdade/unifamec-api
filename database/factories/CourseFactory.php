<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; // Importe o Str

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Define um título aleatório
        $title = fake()->unique()->randomElement([
            'Engenharia de Software',
            'Ciência da Computação',
            'Sistemas de Informação',
            'Direito',
            'Administração',
            'Medicina',
            'Psicologia',
            'Jornalismo',
            'Arquitetura e Urbanismo',
            'Engenharia Civil'
        ]);
        
        // Gera o slug a partir do título
        $slug = Str::slug($title);

        return [
            'title' => $title,
            'slug' => $slug,
            'description' => fake()->paragraph(2), // 2 frases de descrição
            'content' => fake()->paragraph(20), // 20 frases de conteúdo
            'duration_semesters' => fake()->randomElement(['8 Semestres', '10 Semestres']),
            'price' => fake()->randomFloat(2, 500, 2500), // Preço entre 500 e 2500
            'modality' => fake()->randomElement(['Presencial', 'EAD', 'Semipresencial']),
            'thumbnail_url' => 'https://via.placeholder.com/400x300.png?text=' . urlencode($title), // Imagem placeholder
            'header_image_url' => 'https://via.placeholder.com/1200x400.png?text=' . urlencode($title), // Imagem placeholder
            'is_featured' => fake()->boolean(20), // 20% de chance de ser "featured"
        ];
    }
}