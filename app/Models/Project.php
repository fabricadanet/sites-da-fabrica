<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids; // Importar

class Project extends Model
{
    use HasFactory, HasUuids; // Adicionar

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    protected $fillable = [
        'user_id',
        'template_id',
        'name',
        'json_data',
        'status',
        'subdomain',
        'custom_domain',
    ];

    protected $casts = [
        'json_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
    
    // Helper para o deployer saber qual domínio usar
    public function getDeploymentHost(): string
    {
        return $this->custom_domain ?? $this->subdomain;
    }
}