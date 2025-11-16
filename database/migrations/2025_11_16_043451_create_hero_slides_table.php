<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Título principal
            $table->string('subtitle')->nullable(); // Texto menor abaixo do título
            $table->string('button_text')->nullable(); // Ex: "Saiba Mais"
            $table->string('button_link')->nullable(); // Ex: "/cursos/engenharia"
            $table->string('image_url_desktop'); // Imagem de fundo (Desktop)
            $table->string('image_url_mobile')->nullable(); // Imagem de fundo (Mobile)
            $table->integer('order')->default(0); // Ordem de exibição
            $table->boolean('is_active')->default(true); // Para ativar/desativar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};