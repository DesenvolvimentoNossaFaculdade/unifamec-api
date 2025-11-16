<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar; // Importe a classe aqui

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpa o cache inicial (Boa prática)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'api';

        // --- 1. CRIAR TODAS AS PERMISSÕES PRIMEIRO ---
        Permission::create(['guard_name' => $guard, 'name' => 'noticias:gerenciar']);
        Permission::create(['guard_name' => $guard, 'name' => 'banners:gerenciar']);
        Permission::create(['guard_name' => $guard, 'name' => 'cursos:gerenciar']);
        Permission::create(['guard_name' => $guard, 'name' => 'cursos:editar-preco']);
        Permission::create(['guard_name' => $guard, 'name' => 'documentos:gerenciar']);
        Permission::create(['guard_name' => $guard, 'name' => 'paginas:gerenciar']);
        Permission::create(['guard_name' => $guard, 'name' => 'config:gerenciar']);

        // --- 2. RESETAR O CACHE DE NOVO ---
        // ****** ESTA É A CORREÇÃO MÁGICA ******
        // Força o Spatie a reler o banco e "ver" as permissões que acabamos de criar.
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- 3. CRIAR PAPÉIS E ATRIBUIR AS PERMISSÕES (AGORA VAI) ---
        
        // Papel Marketing
        $marketing = Role::create(['guard_name' => $guard, 'name' => 'Marketing']);
        $marketing->givePermissionTo([
            'noticias:gerenciar',
            'banners:gerenciar',
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