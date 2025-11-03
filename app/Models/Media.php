<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Media extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'site_id',
        'user_id',
        'name',
        'file_name',
        'mime_type',
        'size',
        'disk',
        'path',
        'url',
        'type',
        'meta',
        'usage_count',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    // Relações
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Escopos
    public function scopeImages($query)
    {
        return $query->where('type', 'image');
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeDocuments($query)
    {
        return $query->where('type', 'document');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeRecent($query)
    {
        return $query->orderByDesc('created_at');
    }

    // Ações
    public function incrementUsage()
    {
        $this->increment('usage_count');
        return $this;
    }

    public function decrementUsage()
    {
        if ($this->usage_count > 0) {
            $this->decrement('usage_count');
        }

        return $this;
    }

    public function delete()
    {
        // Deletar arquivo do storage
        \Storage::disk($this->disk)->delete($this->path);

        return parent::delete();
    }

    // Helpers
    public function getFileSize()
    {
        return $this->formatBytes($this->size);
    }

    public function isImage()
    {
        return $this->type === 'image';
    }

    public function isVideo()
    {
        return $this->type === 'video';
    }

    public function isUsed()
    {
        return $this->usage_count > 0;
    }

    public function getImageDimensions()
    {
        if (!$this->isImage()) {
            return null;
        }

        return [
            'width' => $this->meta['width'] ?? null,
            'height' => $this->meta['height'] ?? null,
        ];
    }

    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    public function getTypeLabel()
    {
        return match ($this->type) {
            'image' => 'Imagem',
            'video' => 'Vídeo',
            'document' => 'Documento',
            'other' => 'Outro',
            default => 'Desconhecido',
        };
    }
}
