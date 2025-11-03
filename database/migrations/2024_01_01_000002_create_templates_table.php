<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('category'); // geral, advogados, eventos, medicos, etc
            $table->string('thumbnail_url')->nullable(); // URL da imagem/preview
            
            // Estrutura do template
            $table->longText('html_content'); // Conteúdo HTML base
            $table->json('config_schema'); // Schema de configuração (JSON)
            $table->json('default_config'); // Configuração padrão (JSON)
            
            // Exemplo:
            // config_schema: {
            //   "title": { "type": "string", "required": true },
            //   "primary_color": { "type": "color" }
            // }
            // default_config: {
            //   "title": "Seu Negócio",
            //   "primary_color": "#3B82F6"
            // }
            
            $table->json('sections')->nullable(); // Seções disponíveis
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
