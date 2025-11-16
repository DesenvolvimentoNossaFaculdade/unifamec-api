<?php

namespace Database\Seeders;

use App\Models\NavigationLink;
use App\Models\NavigationMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criar o Menu Principal
        $mainMenu = NavigationMenu::create([
            'name' => 'Menu Principal',
            'slug' => 'main-nav'
        ]);

        // Links Raiz
        NavigationLink::create(['navigation_menu_id' => $mainMenu->id, 'label' => 'Home', 'url' => '/', 'order' => 1]);
        NavigationLink::create(['navigation_menu_id' => $mainMenu->id, 'label' => 'Institucional', 'url' => '/institucional', 'order' => 2]);
        $courses = NavigationLink::create(['navigation_menu_id' => $mainMenu->id, 'label' => 'Cursos', 'url' => '/cursos', 'order' => 3]);
        NavigationLink::create(['navigation_menu_id' => $mainMenu->id, 'label' => 'Notícias', 'url' => '/noticias', 'order' => 4]);
        NavigationLink::create(['navigation_menu_id' => $mainMenu->id, 'label' => 'Contato', 'url' => '/contato', 'order' => 5]);

        // Sub-links (Dropdown) de Cursos
        NavigationLink::create(['navigation_menu_id' => $mainMenu->id, 'parent_id' => $courses->id, 'label' => 'Engenharia de Software', 'url' => '/cursos/engenharia-de-software', 'order' => 1]);
        NavigationLink::create(['navigation_menu_id' => $mainMenu->id, 'parent_id' => $courses->id, 'label' => 'Direito', 'url' => '/cursos/direito', 'order' => 2]);
        NavigationLink::create(['navigation_menu_id' => $mainMenu->id, 'parent_id' => $courses->id, 'label' => 'Administração', 'url' => '/cursos/administracao', 'order' => 3]);

        // 2. Criar o Menu do Rodapé
        $footerMenu = NavigationMenu::create([
            'name' => 'Menu Rodapé',
            'slug' => 'footer-nav'
        ]);

        NavigationLink::create(['navigation_menu_id' => $footerMenu->id, 'label' => 'Política de Privacidade', 'url' => '/legal/privacidade', 'order' => 1]);
        NavigationLink::create(['navigation_menu_id' => $footerMenu->id, 'label' => 'Termos de Uso', 'url' => '/legal/termos-de-uso', 'order' => 2]);
        NavigationLink::create(['navigation_menu_id' => $footerMenu->id, 'label' => 'Ouvidoria', 'url' => '/ouvidoria', 'order' => 3]);
    }
}