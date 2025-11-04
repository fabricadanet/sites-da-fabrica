<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'thumbnail_url',
        'html_content',
        'config_schema',
        'default_config',
        'sections',
        'is_active',
        'order',
        'display_name',
        'status',
        'github_path',
        'schema',
    ];

    protected $casts = [
        'config_schema' => 'array',
        'default_config' => 'array',
        'sections' => 'array',
        'is_active' => 'boolean',
        'schema' => 'array',
    ];

    // Relações
    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    // Escopos
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Helpers
    public function getImageUrlAttribute()
    {
        return $this->thumbnail_url ?? asset('images/templates/placeholder.jpg');
    }

    public function getDefaultsAttribute()
    {
        return collect($this->default_config)->mapWithKeys(function ($value, $key) {
            return [$key => $value];
        });
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
