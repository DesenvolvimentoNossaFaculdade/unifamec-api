<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criar o Admin Geral
        $admin = User::factory()->create([
            'name' => 'Admin Master',
            'email' => 'admin@unifamec.com',
            'title' => 'Administrador Geral',
        ]);
        $admin->assignRole('Admin Geral'); // <-- Atribui o Papel

        // 2. Criar um usuário do Marketing
        $marketingUser = User::factory()->create([
            'name' => 'João Marketing',
            'email' => 'marketing@unifamec.com',
            'title' => 'Analista de Marketing',
        ]);
        $marketingUser->assignRole('Marketing'); // <-- Atribui o Papel

        // 3. Criar um usuário do Pedagógico
        $pedagogicoUser = User::factory()->create([
            'name' => 'Maria Pedagógico',
            'email' => 'pedagogico@unifamec.com',
            'title' => 'Coordenadora Pedagógica',
        ]);
        $pedagogicoUser->assignRole('Pedagógico'); // <-- Atribui o Papel

        // 4. Criar um usuário da Secretaria
        $secretariaUser = User::factory()->create([
            'name' => 'Carlos Secretaria',
            'email' => 'secretaria@unifamec.com',
            'title' => 'Secretário Acadêmico',
        ]);
        $secretariaUser->assignRole('Secretaria'); // <-- Atribui o Papel

        // 5. Criar 5 Coordenadores (que são diferentes do Pedagógico)
        $coordinators = User::factory(5)->create();
        foreach ($coordinators as $coord) {
            $coord->assignRole('Coordenador');
        }
    }
}