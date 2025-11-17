<?php

namespace Database\Seeders;

use App\Models\GalleryCategory;
use App\Models\GalleryImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $catTodos = GalleryCategory::create(['name' => 'Todos', 'slug' => 'todos', 'order' => 1]);
        $catLabs = GalleryCategory::create(['name' => 'Laboratórios', 'slug' => 'laboratorios', 'order' => 2]);
        $catClinicas = GalleryCategory::create(['name' => 'Clínicas', 'slug' => 'clinicas', 'order' => 3]);
        $catBib = GalleryCategory::create(['name' => 'Biblioteca', 'slug' => 'biblioteca', 'order' => 4]);

        GalleryImage::factory()->create([
            'gallery_category_id' => $catLabs->id,
            'url' => 'https://via.placeholder.com/600x400.png?text=Laboratorio+1',
            'alt' => 'Laboratório de Informática',
        ]);
        GalleryImage::factory()->create([
            'gallery_category_id' => $catClinicas->id,
            'url' => 'https://via.placeholder.com/600x400.png?text=Clinica+1',
            'alt' => 'Clínica de Psicologia',
        ]);
        GalleryImage::factory()->create([
            'gallery_category_id' => $catBib->id,
            'url' => 'https://via.placeholder.com/600x400.png?text=Biblioteca+1',
            'alt' => 'Biblioteca Central',
        ]);
    }
}