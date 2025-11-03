<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->cascadeOnDelete();
            $table->foreignId('page_id')->nullable()->constrained()->nullOnDelete();
            
            // Visitante
            $table->string('visitor_id'); // UUID ou cookie ID
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referrer')->nullable();
            
            // Localização
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            
            // Device
            $table->string('device_type'); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            
            // Evento
            $table->string('event_type'); // page_view, click, form_submit, etc
            $table->json('event_data')->nullable();
            
            // Duração
            $table->integer('session_duration')->nullable(); // em segundos
            
            $table->timestamp('visited_at');
            
            $table->index(['site_id', 'visited_at']);
            $table->index(['visitor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};
