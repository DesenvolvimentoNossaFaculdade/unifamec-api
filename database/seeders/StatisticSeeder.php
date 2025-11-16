<?php

namespace Database\Seeders;

use App\Models\Statistic; // Importe
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatisticSeeder extends Seeder
{
    public function run(): void
    {
        Statistic::factory()->create([
            'title' => 'Cursos de Graduação',
            'value' => 30,
            'prefix' => '+',
            'suffix' => null,
            'order' => 1,
        ]);

        Statistic::factory()->create([
            'title' => 'Alunos Formados',
            'value' => 15,
            'prefix' => '+',
            'suffix' => 'K',
            'order' => 2,
        ]);

        Statistic::factory()->create([
            'title' => 'Anos de Tradição',
            'value' => 25,
            'prefix' => null,
            'suffix' => null,
            'order' => 3,
        ]);

        Statistic::factory()->create([
            'title' => 'Nossos Laboratórios',
            'value' => 40,
            'prefix' => '+',
            'suffix' => null,
            'order' => 4,
        ]);
    }
}