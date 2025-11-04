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
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // geral, advogados, eventos, medicos, etc
            $table->string('thumbnail_url')->nullable(); // URL da imagem/preview
            $table->string('status')->default('inactive'); // 'active', 'inactive'
            $table->string('github_path')->nullable(); // ex: '/templates/advocacia-classico'
            $table->json('schema')->nullable();
            
            // Estrutura do template
            $table->longText('html_content')->nullable(); // Conteúdo HTML base
            $table->json('config_schema')->nullable();// Schema de configuração (JSON)
            $table->json('default_config')->nullable(); // Configuração padrão (JSON)
            
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
