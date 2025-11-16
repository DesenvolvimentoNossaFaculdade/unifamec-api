<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_infos', function (Blueprint $table) {
            $table->id();

            // Contato (para TopHeader e ContactSection)
            $table->string('phone')->nullable(); // Ex: "0800 123 4567"
            $table->string('whatsapp')->nullable(); // Ex: "+55 (88) 91234-5678"
            $table->string('email')->nullable(); // Ex: "contato@unifamec.com"

            // Endereço (para Footer e ContactSection)
            $table->string('address_street')->nullable(); // Ex: "Rua Exemplo, 123"
            $table->string('address_city_state')->nullable(); // Ex: "Juazeiro do Norte, CE"
            $table->string('address_zip')->nullable(); // Ex: "63000-000"
            $table->string('google_maps_url')->nullable(); // Link do Google Maps

            // Redes Sociais (para Footer)
            $table->string('social_facebook')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_youtube')->nullable();
            $table->string('social_tiktok')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_infos');
    }
};