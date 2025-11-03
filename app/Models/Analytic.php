<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'site_id',
        'page_id',
        'visitor_id',
        'ip_address',
        'user_agent',
        'referrer',
        'country',
        'city',
        'device_type',
        'browser',
        'os',
        'event_type',
        'event_data',
        'session_duration',
        'visited_at',
    ];

    protected $casts = [
        'event_data' => 'array',
        'visited_at' => 'datetime',
    ];

    // Relações
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    // Escopos
    public function scopeOfSite($query, $siteId)
    {
        return $query->where('site_id', $siteId);
    }

    public function scopeOfPage($query, $pageId)
    {
        return $query->where('page_id', $pageId);
    }

    public function scopeByEventType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    public function scopePageViews($query)
    {
        return $query->where('event_type', 'page_view');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('visited_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('visited_at', now()->month)
                     ->whereYear('visited_at', now()->year);
    }

    public function scopeThisYear($query)
    {
        return $query->whereYear('visited_at', now()->year);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('visited_at', '>=', now()->subDays($days));
    }

    // Stats
    public static function getPageViews($siteId, $days = 30)
    {
        return self::pageViews()
                   ->ofSite($siteId)
                   ->recent($days)
                   ->count();
    }

    public static function getUniqueVisitors($siteId, $days = 30)
    {
        return self::pageViews()
                   ->ofSite($siteId)
                   ->recent($days)
                   ->distinct('visitor_id')
                   ->count();
    }

    public static function getTopPages($siteId, $limit = 10, $days = 30)
    {
        return self::pageViews()
                   ->ofSite($siteId)
                   ->recent($days)
                   ->groupBy('page_id')
                   ->selectRaw('page_id, count(*) as views')
                   ->orderByDesc('views')
                   ->limit($limit)
                   ->with('page')
                   ->get();
    }

    public static function getTopReferrers($siteId, $limit = 10, $days = 30)
    {
        return self::pageViews()
                   ->ofSite($siteId)
                   ->recent($days)
                   ->whereNotNull('referrer')
                   ->groupBy('referrer')
                   ->selectRaw('referrer, count(*) as views')
                   ->orderByDesc('views')
                   ->limit($limit)
                   ->get();
    }

    public static function getDeviceDistribution($siteId, $days = 30)
    {
        return self::pageViews()
                   ->ofSite($siteId)
                   ->recent($days)
                   ->groupBy('device_type')
                   ->selectRaw('device_type, count(*) as count')
                   ->get()
                   ->pluck('count', 'device_type');
    }

    public static function getCountryDistribution($siteId, $limit = 10, $days = 30)
    {
        return self::pageViews()
                   ->ofSite($siteId)
                   ->recent($days)
                   ->whereNotNull('country')
                   ->groupBy('country')
                   ->selectRaw('country, count(*) as views')
                   ->orderByDesc('views')
                   ->limit($limit)
                   ->get();
    }

    // Helpers
    public function getDeviceLabel()
    {
        return match ($this->device_type) {
            'mobile' => 'Celular',
            'tablet' => 'Tablet',
            'desktop' => 'Desktop',
            default => 'Desconhecido',
        };
    }
}
