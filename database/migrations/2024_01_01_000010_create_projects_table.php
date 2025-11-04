<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique(); // Essencial para o nome da stack (ex: site-uuid)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('template_id')->constrained()->onDelete('restrict');
            
            $table->string('name'); // Nome interno (ex: "Meu Site de Advocacia")
            $table->json('json_data')->nullable(); // Onde os dados do formulário são salvos
            
            $table->string('status')->default('draft'); // draft, pending, publishing, published, failed
            
            $table->string('subdomain')->nullable()->unique(); // ex: 'minha-padaria.sitesdafabrica.com.br'
            $table->string('custom_domain')->nullable()->unique(); // ex: 'www.minhapadaria.com'
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};