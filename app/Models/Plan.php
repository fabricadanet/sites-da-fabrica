<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stripe_id',
        'mercado_pago_id',
        'max_sites',
        'max_storage_gb',
        'max_bandwidth_gb',
        'custom_domain',
        'branding_removal',
        'ssl_included',
        'api_access',
        'support_priority',
        'features',
        'is_active',
        'order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'custom_domain' => 'boolean',
        'branding_removal' => 'boolean',
        'ssl_included' => 'boolean',
        'api_access' => 'boolean',
        'is_active' => 'boolean',
        'features' => 'array',
    ];

    // Relações
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    // Escopos úteis
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Helpers
    public function getFormattedPriceAttribute()
    {
        return 'R$ ' . number_format($this->price, 2, ',', '.');
    }

    public function isFree()
    {
        return $this->price == 0;
    }
}
