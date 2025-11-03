<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomDomain extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'site_id',
        'domain',
        'is_verified',
        'dns_verification_code',
        'verified_at',
        'ssl_enabled',
        'ssl_certificate_id',
        'ssl_expires_at',
        'status',
        'error_message',
        'dns_records',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'ssl_enabled' => 'boolean',
        'ssl_expires_at' => 'datetime',
        'dns_records' => 'array',
    ];

    // Relações
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    // Escopos
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Ações
    public function verify()
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now(),
            'status' => 'active',
            'error_message' => null,
        ]);

        return $this;
    }

    public function setError($message)
    {
        $this->update([
            'status' => 'error',
            'error_message' => $message,
        ]);

        return $this;
    }

    public function generateDnsCode()
    {
        $code = 'sdf-' . bin2hex(random_bytes(16));
        $this->update(['dns_verification_code' => $code]);

        return $code;
    }

    public function getDnsRecords()
    {
        return $this->dns_records ?? [];
    }

    public function isExpiringSoon()
    {
        if (!$this->ssl_expires_at) {
            return false;
        }

        return now()->diffInDays($this->ssl_expires_at) <= 30;
    }

    // Helpers
    public function getStatusLabel()
    {
        return match ($this->status) {
            'pending' => 'Pendente',
            'active' => 'Ativo',
            'inactive' => 'Inativo',
            'error' => 'Erro',
            default => 'Desconhecido',
        };
    }
}
