<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Arquivo
            $table->string('name');
            $table->string('file_name')->unique();
            $table->string('mime_type');
            $table->integer('size'); // em bytes
            
            // Caminho
            $table->string('disk')->default('public'); // storage disk
            $table->string('path'); // caminho completo no storage
            $table->string('url'); // URL pública
            
            // Tipo
            $table->enum('type', ['image', 'video', 'document', 'other'])
                  ->default('image');
            
            // Meta
            $table->json('meta')->nullable(); // width, height, duration, etc
            
            // Uso
            $table->integer('usage_count')->default(0); // Quantas vezes é usada
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['site_id', 'type']);
            $table->index(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
