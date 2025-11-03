<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'canceled_at',
        'stripe_subscription_id',
        'mercado_pago_subscription_id',
        'current_price',
        'auto_renew',
        'next_billing_date',
        'meta',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'canceled_at' => 'datetime',
        'next_billing_date' => 'datetime',
        'auto_renew' => 'boolean',
        'current_price' => 'decimal:2',
        'meta' => 'array',
    ];

    // Relações
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    // Escopos
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeTrial($query)
    {
        return $query->where('status', 'trial');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', 'canceled');
    }

    // Verifica status
    public function isActive()
    {
        return $this->status === 'active' && (!$this->ends_at || $this->ends_at->isFuture());
    }

    public function isExpired()
    {
        return $this->status === 'expired' || ($this->ends_at && $this->ends_at->isPast());
    }

    public function isCanceled()
    {
        return $this->status === 'canceled';
    }

    public function isOnTrial()
    {
        return $this->status === 'trial' && $this->trial_ends_at?->isFuture();
    }

    // Ações
    public function cancel()
    {
        $this->update([
            'status' => 'canceled',
            'canceled_at' => now(),
            'ends_at' => now(),
        ]);
    }

    public function reactivate()
    {
        $this->update([
            'status' => 'active',
            'canceled_at' => null,
            'auto_renew' => true,
        ]);
    }

    public function changePlan(Plan $newPlan)
    {
        $this->update([
            'plan_id' => $newPlan->id,
            'current_price' => $newPlan->price,
        ]);

        return $this;
    }

    // Helpers
    public function daysRemaining()
    {
        if (!$this->ends_at) {
            return null;
        }

        return now()->diffInDays($this->ends_at, false);
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'trial' => 'Período de Teste',
            'active' => 'Ativa',
            'past_due' => 'Pagamento Pendente',
            'canceled' => 'Cancelada',
            'expired' => 'Expirada',
            default => 'Desconhecida',
        };
    }
}
