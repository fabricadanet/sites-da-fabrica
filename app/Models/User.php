<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * ==========================================
     * RELACIONAMENTOS
     * ==========================================
     */

    /**
     * Obter as assinaturas do usuário
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Obter a assinatura ativa atual
     */
    public function subscription()
    {
        return $this->subscriptions()
            ->orderByDesc('created_at')
            ->first();
    }

    /**
     * Obter os sites do usuário
     */
    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    /**
     * ==========================================
     * HELPERS & MÉTODOS ÚTEIS
     * ==========================================
     */

    /**
     * Verificar se usuário tem assinatura ativa
     */
    public function hasActiveSubscription()
    {
        $subscription = $this->subscription();
        return $subscription && $subscription->isActive();
    }

    /**
     * Verificar se usuário está em período de teste
     */
    public function isOnTrial()
    {
        $subscription = $this->subscription();
        return $subscription && $subscription->isOnTrial();
    }

    /**
     * Obter dias restantes do teste
     */
    public function trialDaysRemaining()
    {
        $subscription = $this->subscription();
        if (!$subscription || !$subscription->isOnTrial()) {
            return 0;
        }
        return $subscription->daysRemaining() ?? 0;
    }

    /**
     * Obter o plano atual
     */
    public function currentPlan()
    {
        $subscription = $this->subscription();
        return $subscription ? $subscription->plan : null;
    }

    /**
     * Verificar se pode criar mais sites
     */
    public function canCreateMoreSites()
    {
        $subscription = $this->subscription();
        if (!$subscription) {
            return false;
        }

        $plan = $subscription->plan;
        if (!$plan) {
            return false;
        }

        // Se max_sites for -1 ou null, significa ilimitado
        if ($plan->max_sites == -1 || is_null($plan->max_sites)) {
            return true;
        }

        return $this->sites()->count() < $plan->max_sites;
    }

    /**
     * Obter limite de sites
     */
    public function sitesLimit()
    {
        $plan = $this->currentPlan();
        if (!$plan) {
            return 0;
        }

        return $plan->max_sites == -1 ? 'Ilimitado' : $plan->max_sites;
    }

    /**
     * Obter sites usados
     */
    public function sitesUsed()
    {
        return $this->sites()->count();
    }

    /**
     * Obter percentual de uso de sites
     */
    public function sitesUsagePercentage()
    {
        $plan = $this->currentPlan();
        if (!$plan || $plan->max_sites == -1) {
            return 0;
        }

        $used = $this->sitesUsed();
        $limit = $plan->max_sites;

        if ($limit == 0) {
            return 0;
        }

        return round(($used / $limit) * 100);
    }

    /**
     * Obter plano em formato legível
     */
    public function planName()
    {
        $subscription = $this->subscription();
        if (!$subscription || !$subscription->plan) {
            return 'Sem Plano';
        }
        return $subscription->plan->name;
    }

    /**
     * Obter status da assinatura em formato legível
     */
    public function subscriptionStatus()
    {
        $subscription = $this->subscription();
        if (!$subscription) {
            return 'Nenhuma assinatura';
        }
        return $subscription->status_label;
    }

    /**
     * Obter preço do plano atual
     */
    public function currentPrice()
    {
        $subscription = $this->subscription();
        if (!$subscription) {
            return 0;
        }
        return $subscription->current_price ?? $subscription->plan?->price ?? 0;
    }

    /**
     * Obter data de próxima cobrança
     */
    public function nextBillingDate()
    {
        $subscription = $this->subscription();
        return $subscription ? $subscription->next_billing_date : null;
    }

    /**
     * Verificar se é assinante premium
     */
    public function isPremium()
    {
        $subscription = $this->subscription();
        if (!$subscription || !$subscription->isActive()) {
            return false;
        }

        $plan = $subscription->plan;
        return $plan && $plan->price > 0;
    }

    /**
     * Verificar se usuário pode acessar feature
     */
    public function canUseFeature($feature)
    {
        $plan = $this->currentPlan();
        if (!$plan) {
            return false;
        }

        // Se o plano tem features, verificar se a feature está lá
        $features = $plan->features ?? [];
        
        if (is_string($features)) {
            $features = json_decode($features, true);
        }

        return in_array($feature, $features);
    }

    /**
     * Obter informações do usuário para API
     */
    public function toArray()
    {
        $data = parent::toArray();
        
        $subscription = $this->subscription();
        $data['subscription'] = [
            'plan' => $subscription?->plan?->name,
            'status' => $subscription?->status,
            'is_on_trial' => $this->isOnTrial(),
            'trial_days_remaining' => $this->trialDaysRemaining(),
        ];

        return $data;
    }
}