<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigation_links', function (Blueprint $table) {
            $table->id();

            // A qual menu este link pertence?
            $table->foreignId('navigation_menu_id')->constrained('navigation_menus')->onDelete('cascade');

            // Este link é um sub-item de outro link?
            $table->foreignId('parent_id')->nullable()->constrained('navigation_links')->onDelete('cascade');

            $table->string('label'); // "Cursos"
            $table->string('url'); // "/cursos" ou "https://google.com"
            $table->integer('order')->default(0);
            $table->boolean('target_blank')->default(false); // Abrir em nova aba?

            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('navigation_links'); }
};