<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa cache do Spatie para evitar bugs de cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        
        $guard = 'api'; // Como estamos usando Next.js + Laravel API

        // --- 1. CRIAR TODAS AS PERMISSÕES ---
        $permissions = [
            'noticias:gerenciar',
            'hero:gerenciar',
            'galeria:gerenciar',
            'cursos:gerenciar',
            'cursos:editar-preco',
            'cursos:editar-imagem',
            'documentos:gerenciar',
            'paginas:gerenciar',
            'config:gerenciar',
            // ADICIONADO: Permissão específica para Locais de Prova
            'locais-prova:gerenciar', 
        ];

        foreach ($permissions as $permission) {
            // createOrFirst evita erro de duplicidade se rodar o seeder 2x
            Permission::firstOrCreate(['guard_name' => $guard, 'name' => $permission]);
        }

        // --- 2. ATRIBUIR AOS PAPÉIS ---
        
        // MARKETING
        $marketing = Role::firstOrCreate(['guard_name' => $guard, 'name' => 'Marketing']);
        $marketing->syncPermissions([ // syncPermissions remove as antigas e põe as novas
            'noticias:gerenciar',
            'hero:gerenciar',
            'galeria:gerenciar',
            'cursos:gerenciar',
            'documentos:gerenciar',
        ]);

        // PEDAGÓGICO
        $pedagogico = Role::firstOrCreate(['guard_name' => $guard, 'name' => 'Pedagógico']);
        $pedagogico->syncPermissions([
            'cursos:gerenciar',
            'documentos:gerenciar',
            'locais-prova:gerenciar', // <--- ELES PODEM GERENCIAR LOCAIS
        ]);
        
        // SECRETARIA
        $secretaria = Role::firstOrCreate(['guard_name' => $guard, 'name' => 'Secretaria']);
        $secretaria->syncPermissions([
            'cursos:editar-preco',
            'locais-prova:gerenciar', // <--- SECRETARIA TAMBÉM GERALMENTE PODE
        ]);

        // COORDENADOR
        $coordenador = Role::firstOrCreate(['guard_name' => $guard, 'name' => 'Coordenador']);
        // Dê permissões ao coordenador se necessário

        // ADMIN GERAL (Super Admin)
        $admin = Role::firstOrCreate(['guard_name' => $guard, 'name' => 'Admin Geral']);
        // Admin Geral geralmente bypassa tudo via Gate::before no AuthServiceProvider, 
        // mas se quiser garantir:
        $admin->syncPermissions(Permission::all());
    }
}