<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            
            // Status da assinatura
            $table->enum('status', ['trial', 'active', 'past_due', 'canceled', 'expired'])
                  ->default('trial');
            
            // Datas
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            
            // Pagamento
            $table->string('stripe_subscription_id')->nullable();
            $table->string('mercado_pago_subscription_id')->nullable();
            $table->decimal('current_price', 10, 2)->nullable(); // Preço pago
            
            // Renovação automática
            $table->boolean('auto_renew')->default(true);
            $table->timestamp('next_billing_date')->nullable();
            
            // Dados da assinatura
            $table->json('meta')->nullable(); // Dados adicionais
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
