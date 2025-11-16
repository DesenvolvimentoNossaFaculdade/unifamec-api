<?php

namespace Database\Seeders;

use App\Models\SiteInfo; // Importe
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteInfoSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa a tabela para garantir que só temos 1 registro
        SiteInfo::truncate();

        // Cria o registro principal
        SiteInfo::create([
            'phone' => '0800 123 4567',
            'whatsapp' => '+55 (88) 91234-5678',
            'email' => 'contato@unifamec.com',
            'address_street' => 'Av. Castelo Branco, 100',
            'address_city_state' => 'Juazeiro do Norte, CE',
            'address_zip' => '63010-485',
            'google_maps_url' => 'https://maps.app.goo.gl/exemplo',
            'social_facebook' => 'https://facebook.com/unifamec',
            'social_instagram' => 'https://instagram.com/unifamec',
            'social_linkedin' => 'https://linkedin.com/unifamec',
            'social_youtube' => 'https://youtube.com/unifamec',
            'social_tiktok' => 'https://tiktok.com/@unifamec',
        ]);
    }
}