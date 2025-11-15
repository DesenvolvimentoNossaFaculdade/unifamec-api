<?php

namespace Database\Seeders;

use App\Models\User; // Importe
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Criar um Admin Mestre
        User::factory()->admin()->create([
            'name' => 'Admin Master',
            'email' => 'admin@unifamec.com',
        ]);

        // 2. Criar 5 Coordenadores
        User::factory(5)->coordinator()->create();

        // 3. Criar 10 Usuários comuns (ex: professores)
        User::factory(10)->create();
    }
}