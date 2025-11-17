<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        Document::factory()->create([
            'title' => 'Manual do Aluno 2026',
            'category' => 'manual',
        ]);
        Document::factory()->create([
            'title' => 'Calendário Acadêmico 2026.1',
            'category' => 'calendario',
        ]);
        Document::factory()->create([
            'title' => 'Edital Vestibular 2026',
            'category' => 'documento',
        ]);
    }
}