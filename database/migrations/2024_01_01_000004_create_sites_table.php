<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('template_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained()->nullOnDelete();
            
            // Identificadores
            $table->string('name'); // Nome do site
            $table->string('slug')->unique(); // URL slug único
            
            // Domínio
            $table->string('subdomain')->unique()->nullable(); // subdominio.sitesdafabrica.com.br
            $table->string('custom_domain')->unique()->nullable(); // Domínio personalizado
            $table->boolean('use_custom_domain')->default(false);
            
            // Configuração
            $table->json('config'); // Configuração customizada do site
            // Exemplo:
            // {
            //   "title": "Consultoria Silva",
            //   "description": "Consultoria empresarial",
            //   "primary_color": "#3B82F6",
            //   "logo_url": "...",
            //   "whatsapp": "5511999887766"
            // }
            
            // Status
            $table->enum('status', ['draft', 'published', 'archived'])
                  ->default('draft');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            
            // Limite de recursos
            $table->integer('current_storage_used_mb')->default(0);
            $table->integer('current_bandwidth_used_gb')->default(0);
            
            // Analytics básico
            $table->integer('total_views')->default(0);
            $table->integer('unique_visitors')->default(0);
            $table->timestamp('last_viewed_at')->nullable();
            
            // Meta
            $table->json('meta')->nullable();
            $table->timestamp('last_modified_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'status']);
            $table->index(['slug', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
