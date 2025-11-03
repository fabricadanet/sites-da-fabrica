<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            
            // Tipo de seção
            $table->string('type'); // hero, features, gallery, testimonials, contact_form, etc
            
            // Conteúdo
            $table->json('data'); // Dados específicos de cada tipo de seção
            // Exemplo para hero:
            // {
            //   "title": "Bem-vindo",
            //   "subtitle": "Sua melhor solução",
            //   "button_text": "Saiba mais",
            //   "button_url": "/services",
            //   "image_url": "..."
            // }
            
            // Estilo
            $table->json('style')->nullable(); // Cores, fonte, espaçamento customizado
            
            // Visibilidade
            $table->boolean('is_visible')->default(true);
            
            // Ordenação
            $table->integer('order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['page_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
