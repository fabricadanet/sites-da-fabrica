<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    /**
     * Criar nova seção
     */
    public function store(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        $validated = $request->validate([
            'page_id' => 'required|exists:pages,id',
            'type' => 'required|string|in:hero,features,gallery,testimonials,contact_form,pricing,team,faq,newsletter,cta',
            'data' => 'nullable|array',
        ]);

        // Verificar se a página pertence ao site
        $page = Page::findOrFail($validated['page_id']);
        if ($page->site_id !== $site->id) {
            return response()->json([
                'message' => 'Página não encontrada',
            ], 404);
        }

        // Obter ordem máxima
        $maxOrder = $page->sections()->max('order') ?? -1;

        $section = $page->sections()->create([
            'type' => $validated['type'],
            'data' => $validated['data'] ?? $this->getDefaultData($validated['type']),
            'order' => $maxOrder + 1,
            'is_visible' => true,
        ]);

        return response()->json([
            'message' => 'Seção adicionada!',
            'section' => $section,
        ]);
    }

    /**
     * Atualizar seção
     */
    public function update(Request $request, Site $site, Section $section)
    {
        $this->authorize('update', $site);

        // Verificar acesso
        if ($section->page->site_id !== $site->id) {
            abort(404);
        }

        $validated = $request->validate([
            'data' => 'array',
            'style' => 'nullable|array',
            'is_visible' => 'boolean',
        ]);

        $section->update($validated);

        return response()->json([
            'message' => 'Seção atualizada!',
            'section' => $section,
        ]);
    }

    /**
     * Deletar seção
     */
    public function destroy(Site $site, Section $section)
    {
        $this->authorize('update', $site);

        if ($section->page->site_id !== $site->id) {
            abort(404);
        }

        $section->delete();

        return response()->json([
            'message' => 'Seção deletada!',
        ]);
    }

    /**
     * Reordenar seções
     */
    public function reorder(Request $request, Site $site, Page $page)
    {
        $this->authorize('update', $site);

        if ($page->site_id !== $site->id) {
            abort(404);
        }

        $validated = $request->validate([
            'sections' => 'array',
            'sections.*' => 'integer',
        ]);

        foreach ($validated['sections'] as $order => $sectionId) {
            $page->sections()
                ->where('id', $sectionId)
                ->update(['order' => $order]);
        }

        return response()->json([
            'message' => 'Ordem das seções atualizada!',
        ]);
    }

    /**
     * Dados padrão para cada tipo de seção
     */
    private function getDefaultData($type): array
    {
        return match ($type) {
            'hero' => [
                'title' => 'Bem-vindo ao seu site',
                'subtitle' => 'Adicione uma descrição aqui',
                'button_text' => 'Saiba mais',
                'button_url' => '#',
                'image_url' => null,
                'background_color' => '#3B82F6',
            ],
            'features' => [
                'title' => 'Nossos Recursos',
                'description' => 'Descubra o que tornamos possível',
                'features' => [
                    [
                        'icon' => 'fa-star',
                        'title' => 'Recurso 1',
                        'description' => 'Descrição do recurso',
                    ],
                    [
                        'icon' => 'fa-heart',
                        'title' => 'Recurso 2',
                        'description' => 'Descrição do recurso',
                    ],
                ],
            ],
            'gallery' => [
                'title' => 'Galeria',
                'description' => null,
                'images' => [],
                'columns' => 3,
            ],
            'testimonials' => [
                'title' => 'Depoimentos',
                'testimonials' => [
                    [
                        'name' => 'João Silva',
                        'role' => 'Cliente',
                        'text' => 'Excelente serviço!',
                        'avatar' => null,
                    ],
                ],
            ],
            'contact_form' => [
                'title' => 'Entre em Contato',
                'description' => 'Envie-nos uma mensagem',
                'email' => auth()->user()?->email,
                'show_phone' => true,
                'show_message' => true,
            ],
            'pricing' => [
                'title' => 'Nossos Planos',
                'plans' => [
                    [
                        'name' => 'Básico',
                        'price' => '99',
                        'features' => [],
                    ],
                ],
            ],
            'team' => [
                'title' => 'Nosso Time',
                'members' => [],
            ],
            'faq' => [
                'title' => 'Perguntas Frequentes',
                'faqs' => [
                    [
                        'question' => 'Como funciona?',
                        'answer' => 'Explique como funciona...',
                    ],
                ],
            ],
            'newsletter' => [
                'title' => 'Inscreva-se',
                'description' => 'Receba as últimas novidades',
                'button_text' => 'Inscrever',
            ],
            'cta' => [
                'title' => 'Pronto para começar?',
                'description' => 'Junte-se a milhares de clientes satisfeitos',
                'button_text' => 'Começar Agora',
                'button_url' => '#',
                'background_color' => '#3B82F6',
            ],
            default => [],
        };
    }
}