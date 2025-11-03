<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'page_id',
        'type',
        'data',
        'style',
        'is_visible',
        'order',
    ];

    protected $casts = [
        'data' => 'array',
        'style' => 'array',
        'is_visible' => 'boolean',
    ];

    // Relações
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    // Escopos
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Ações
    public function show()
    {
        $this->update(['is_visible' => true]);
        return $this;
    }

    public function hide()
    {
        $this->update(['is_visible' => false]);
        return $this;
    }

    public function updateData($newData)
    {
        $data = $this->data ?? [];
        $this->data = array_merge($data, $newData);
        $this->save();

        return $this;
    }

    public function updateStyle($newStyle)
    {
        $style = $this->style ?? [];
        $this->style = array_merge($style, $newStyle);
        $this->save();

        return $this;
    }

    // Helpers
    public function getData($key, $default = null)
    {
        return data_get($this->data, $key, $default);
    }

    public function getStyle($key, $default = null)
    {
        return data_get($this->style, $key, $default);
    }

    public function getTypeLabel()
    {
        return match ($this->type) {
            'hero' => 'Seção Hero',
            'features' => 'Recursos',
            'gallery' => 'Galeria',
            'testimonials' => 'Depoimentos',
            'contact_form' => 'Formulário de Contato',
            'pricing' => 'Tabela de Preços',
            'team' => 'Time',
            'faq' => 'Perguntas Frequentes',
            'newsletter' => 'Newsletter',
            'cta' => 'Chamada para Ação',
            default => ucfirst($this->type),
        };
    }

    public function getDefaultData()
    {
        return match ($this->type) {
            'hero' => [
                'title' => 'Bem-vindo ao seu site',
                'subtitle' => 'Sua descrição aqui',
                'button_text' => 'Saiba mais',
                'button_url' => '#',
                'image_url' => null,
            ],
            'features' => [
                'title' => 'Nossos Recursos',
                'features' => [],
            ],
            'contact_form' => [
                'title' => 'Entre em Contato',
                'success_message' => 'Mensagem enviada com sucesso!',
            ],
            default => [],
        };
    }
}
