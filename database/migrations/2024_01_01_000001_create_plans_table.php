<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Gratuito, Básico, Profissional, Empresarial
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Preço mensal
            $table->string('stripe_id')->nullable();
            $table->string('mercado_pago_id')->nullable();
            
            // Limites do plano
            $table->integer('max_sites'); // Máximo de sites
            $table->integer('max_storage_gb'); // Storage em GB
            $table->integer('max_bandwidth_gb'); // Banda em GB
            $table->boolean('custom_domain'); // Permite domínio personalizado
            $table->boolean('branding_removal'); // Remove marca d'água
            $table->boolean('ssl_included'); // SSL incluído
            $table->boolean('api_access'); // Acesso a API
            $table->integer('support_priority'); // 1=email, 2=prioritário, 3=dedicado
            
            // Meta informações
            $table->json('features')->nullable(); // Features adicionais em array
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0); // Ordem de exibição
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
