<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // Criar páginas específicas que vimos no front-end
        $pages = [
            'Institucional',
            'Acadêmico',
            'EAD',
            'Graduação',
            'Infraestrutura', // Para a InfrastructureSection
            'Ouvidoria',
            'Legal',
            'Carreiras',
        ];

        foreach ($pages as $title) {
            Page::factory()->create([
                'title' => $title,
                'slug' => Str::slug($title),
                'content' => '<p>Este é o conteúdo da página ' . $title . '. Edite-me no painel de admin!</p>' . 
                            '<p>' . fake()->paragraphs(10, true) . '</p>',
            ]);
        }

        // Criar mais algumas páginas aleatórias (ex: documentos, manuais)
        Page::factory(5)->create();
    }
}