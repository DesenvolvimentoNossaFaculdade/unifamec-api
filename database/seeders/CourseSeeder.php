<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpa a tabela antes de popular (opcional, mas bom)
        // Course::truncate(); 

        // Usa o Factory para criar 10 registros de cursos
        Course::factory(10)->create();
    }
}