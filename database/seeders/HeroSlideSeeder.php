<?php

namespace Database\Seeders;

use App\Models\HeroSlide; // Importe
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        HeroSlide::factory()->create([
            'title' => 'Transforme seu Futuro na UNIFAMEC',
            'subtitle' => 'Cursos de graduação e pós-graduação com foco total no mercado de trabalho.',
            'button_text' => 'Veja os Cursos',
            'button_link' => '/cursos',
            'order' => 1,
        ]);

        HeroSlide::factory()->create([
            'title' => 'Vestibular 2026 Aberto',
            'subtitle' => 'Inscreva-se agora e dê o primeiro passo na sua carreira.',
            'button_text' => 'Inscreva-se Já',
            'button_link' => '/vestibular',
            'order' => 2,
        ]);
    }
}