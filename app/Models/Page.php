<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'site_id',
        'title',
        'slug',
        'description',
        'meta_description',
        'content',
        'is_published',
        'is_home',
        'is_visible_in_menu',
        'order',
        'layout_type',
        'seo_title',
        'seo_keywords',
        'seo_slug',
        'views_count',
        'last_viewed_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_home' => 'boolean',
        'is_visible_in_menu' => 'boolean',
        'content' => 'array',
        'last_viewed_at' => 'datetime',
    ];

    // Relações
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order');
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

    public function scopeVisibleInMenu($query)
    {
        return $query->where('is_visible_in_menu', true)
                     ->orderBy('order');
    }

    public function scopeBySite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }

    // Ações
    public function publish()
    {
        $this->update(['is_published' => true]);
        return $this;
    }

    public function unpublish()
    {
        $this->update(['is_published' => false]);
        return $this;
    }

    public function makeHomePage()
    {
        // Remove is_home de todas as outras páginas do site
        Page::where('site_id', $this->site_id)
            ->where('id', '!=', $this->id)
            ->update(['is_home' => false]);

        // Define esta como home
        $this->update(['is_home' => true, 'slug' => '/']);
        
        return $this;
    }

    public function recordView()
    {
        $this->increment('views_count');
        $this->update(['last_viewed_at' => now()]);
    }

    // Helpers
    public function getUrl()
    {
        if ($this->is_home) {
            return $this->site->getUrl();
        }

        return $this->site->getUrl() . '/' . $this->slug;
    }

    public function getEditUrl()
    {
        return route('tenant.pages.edit', [
            'site' => $this->site->slug,
            'page' => $this->slug,
        ]);
    }

    public function getSeoTitleAttribute()
    {
        return $this->attributes['seo_title'] ?? $this->title;
    }

    public function getSeoDescriptionAttribute()
    {
        return $this->attributes['seo_keywords'] ?? $this->description;
    }

    public function getMenuItems()
    {
        return $this->site->pages()
                          ->visibleInMenu()
                          ->published()
                          ->get()
                          ->map(function ($page) {
                              return [
                                  'title' => $page->title,
                                  'url' => $page->getUrl(),
                              ];
                          });
    }
}
