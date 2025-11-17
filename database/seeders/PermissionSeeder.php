<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpa o cache inicial
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'api';

        // --- 1. CRIAR TODAS AS PERMISSÕES PRIMEIRO ---
        Permission::create(['guard_name' => $guard, 'name' => 'noticias:gerenciar']);
        Permission::create(['guard_name' => $guard, 'name' => 'hero:gerenciar']);
        Permission::create(['guard_name' => $guard, 'name' => 'galeria:gerenciar']);
        Permission::create(['guard_name' => $guard, 'name' => 'cursos:gerenciar']); // (Pedagógico)
        Permission::create(['guard_name' => $guard, 'name' => 'cursos:editar-preco']); // (Secretaria)
        
        // --- NOVO ---
        Permission::create(['guard_name' => $guard, 'name' => 'cursos:editar-imagem']); // (Marketing)
        
        Permission::create(['guard_name' => $guard, 'name' => 'documentos:gerenciar']);
        
        // (Permissões agora sem uso, mas não há problema em mantê-las)
        Permission::create(['guard_name' => $guard, 'name' => 'paginas:gerenciar']);
        Permission::create(['guard_name' => $guard, 'name' => 'config:gerenciar']);

        // --- 2. RESETAR O CACHE DE NOVO ---
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- 3. CRIAR PAPÉIS E ATRIBUIR AS PERMISSÕES ---
        
        // Papel Marketing
        $marketing = Role::create(['guard_name' => $guard, 'name' => 'Marketing']);
        $marketing->givePermissionTo([
            'noticias:gerenciar',
            'hero:gerenciar',
            'galeria:gerenciar',
            'cursos:editar-imagem', // <-- ATRIBUÍDO AO MARKETING
        ]);

        // Papel Pedagógico
        $pedagogico = Role::create(['guard_name' => $guard, 'name' => 'Pedagógico']);
        $pedagogico->givePermissionTo([
            'cursos:gerenciar',
            'documentos:gerenciar',
        ]);
        
        // Papel Secretaria
        $secretaria = Role::create(['guard_name' => $guard, 'name' => 'Secretaria']);
        $secretaria->givePermissionTo([
            'cursos:editar-preco',
        ]);

        // Papel Coordenador
        $coordenador = Role::create(['guard_name' => $guard, 'name' => 'Coordenador']);
        
        // Papel Admin Geral (Super-Admin)
        $admin = Role::create(['guard_name' => $guard, 'name' => 'Admin Geral']);
    }
}