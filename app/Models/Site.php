<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'template_id',
        'subscription_id',
        'name',
        'slug',
        'subdomain',
        'custom_domain',
        'use_custom_domain',
        'config',
        'status',
        'is_published',
        'published_at',
        'current_storage_used_mb',
        'current_bandwidth_used_gb',
        'total_views',
        'unique_visitors',
        'last_viewed_at',
        'meta',
        'last_modified_at',
    ];

    protected $casts = [
        'config' => 'array',
        'use_custom_domain' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'last_viewed_at' => 'datetime',
        'meta' => 'array',
        'last_modified_at' => 'datetime',
    ];

    // Relações
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function customDomains()
    {
        return $this->hasMany(CustomDomain::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function analytics()
    {
        return $this->hasMany(Analytic::class);
    }

    // Escopos
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Ações
    public function publish()
    {
        $this->update([
            'is_published' => true,
            'status' => 'published',
            'published_at' => now(),
        ]);

        return $this;
    }

    public function unpublish()
    {
        $this->update([
            'is_published' => false,
            'status' => 'draft',
        ]);

        return $this;
    }

    public function archive()
    {
        $this->update([
            'status' => 'archived',
            'is_published' => false,
        ]);

        return $this;
    }

    public function getUrl()
    {
        if ($this->use_custom_domain && $this->custom_domain) {
            return "https://{$this->custom_domain}";
        }

        return "https://{$this->subdomain}.sitesdafabrica.com.br";
    }

    public function getHomePage()
    {
        return $this->pages()->where('is_home', true)->first();
    }

    public function canUseCustomDomain()
    {
        $plan = $this->subscription?->plan;
        
        if (!$plan) {
            return false;
        }

        return $plan->custom_domain;
    }

    public function getStoragePercentage()
    {
        if (!$this->subscription?->plan?->max_storage_gb) {
            return 0;
        }

        $maxMb = $this->subscription->plan->max_storage_gb * 1024;
        return ($this->current_storage_used_mb / $maxMb) * 100;
    }

    public function getBandwidthPercentage()
    {
        if (!$this->subscription?->plan?->max_bandwidth_gb) {
            return 0;
        }

        return ($this->current_bandwidth_used_gb / $this->subscription->plan->max_bandwidth_gb) * 100;
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'draft' => 'Rascunho',
            'published' => 'Publicado',
            'archived' => 'Arquivado',
            default => 'Desconhecido',
        };
    }

    // Helpers para configuração
    public function getConfigValue($key, $default = null)
    {
        return data_get($this->config, $key, $default);
    }

    public function setConfigValue($key, $value)
    {
        $config = $this->config ?? [];
        data_set($config, $key, $value);
        $this->config = $config;

        return $this;
    }
}
