<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            
            // Informações básicas
            $table->string('title');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('meta_description')->nullable(); // Para SEO
            
            // Conteúdo
            $table->longText('content')->nullable(); // JSON com blocos de conteúdo
            
            // Status
            $table->boolean('is_published')->default(false);
            $table->boolean('is_home')->default(false); // Página inicial
            $table->boolean('is_visible_in_menu')->default(true);
            
            // Ordenação
            $table->integer('order')->default(0);
            
            // Layout
            $table->string('layout_type')->default('default'); // default, blank, custom
            
            // SEO
            $table->string('seo_title')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->string('seo_slug')->nullable();
            
            // Analytics
            $table->integer('views_count')->default(0);
            $table->timestamp('last_viewed_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['site_id', 'slug']);
            $table->index(['site_id', 'is_published']);
            $table->unique(['site_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
