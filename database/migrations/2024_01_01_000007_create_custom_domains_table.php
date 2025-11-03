<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('custom_domains', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            
            // Domínio
            $table->string('domain')->unique();
            
            // Verificação
            $table->boolean('is_verified')->default(false);
            $table->string('dns_verification_code')->unique()->nullable();
            $table->timestamp('verified_at')->nullable();
            
            // SSL
            $table->boolean('ssl_enabled')->default(false);
            $table->string('ssl_certificate_id')->nullable();
            $table->timestamp('ssl_expires_at')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'active', 'inactive', 'error'])
                  ->default('pending');
            $table->text('error_message')->nullable();
            
            // Configuração
            $table->json('dns_records')->nullable(); // Registros DNS necessários
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['site_id', 'is_verified']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_domains');
    }
};
