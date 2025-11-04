<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    /**
     * Criar novo site via API
     */
    public function store(Request $request)
    {
        try {
            // Validação
            $validated = $request->validate([
                'name' => 'required|string|min:3|max:100',
                'slug' => 'required|string|min:3|max:50|regex:/^[a-z0-9-]+$/',
                'template_id' => 'required|exists:templates,id',
            ], [
                'name.required' => 'Nome do site é obrigatório',
                'name.min' => 'Nome deve ter pelo menos 3 caracteres',
                'slug.required' => 'URL é obrigatória',
                'slug.regex' => 'URL deve conter apenas letras, números e hífens',
                'template_id.required' => 'Selecione um template',
                'template_id.exists' => 'Template inválido',
            ]);

            // Verificar se slug é único
            if (Site::where('slug', $validated['slug'])->exists()) {
                return response()->json([
                    'message' => 'Esta URL já está em uso. Escolha outra.',
                    'errors' => ['slug' => 'URL duplicada']
                ], 422);
            }

            // Verificar limite de sites
            $subscription = auth()->user()?->subscriptions()->active()->first();
            $maxSites = $subscription?->plan?->max_sites ?? 1;
            $currentSites = auth()->user()->sites()->count();

            if ($currentSites >= $maxSites) {
                return response()->json([
                    'message' => "Você atingiu o limite de {$maxSites} site(s)",
                ], 403);
            }

            // Buscar template
            $template = Template::findOrFail($validated['template_id']);

            // Criar site
            $site = Site::create([
                'user_id' => auth()->id(),
                'template_id' => $template->id,
                'subscription_id' => $subscription?->id,
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'subdomain' => $validated['slug'],
                'config' => $this->getDefaultConfig($validated['name'], $template),
                'status' => 'draft',
                'is_published' => false,
                'meta' => [
                    'created_from_modal' => true,
                    'template_name' => $template->name,
                ],
            ]);

            // Criar página home padrão
            $site->pages()->create([
                'title' => 'Home',
                'slug' => '/',
                'is_home' => true,
                'is_published' => false,
                'is_visible_in_menu' => true,
                'order' => 0,
                'layout_type' => 'default',
            ]);

            return response()->json([
                'message' => 'Site criado com sucesso!',
                'site_id' => $site->id,
                'site_slug' => $site->slug,
                'redirect_url' => route('tenant.sites.edit', ['site' => $site->slug]),
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Site creation error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Erro ao criar site. Tente novamente.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Obter lista de templates para o modal
     */
    public function getTemplates()
    {
        $templates = Template::active()
            ->ordered()
            ->get(['id', 'name', 'slug', 'description', 'category', 'thumbnail_url'])
            ->toArray();

        return response()->json(['templates' => $templates]);
    }

    /**
     * Configuração padrão do site
     */
    private function getDefaultConfig(string $siteName, Template $template): array
    {
        return array_merge(
            [
                'title' => $siteName,
                'description' => 'Seu site profissional',
                'primary_color' => '#3B82F6',
                'secondary_color' => '#8B5CF6',
                'text_color' => '#1F2937',
                'background_color' => '#FFFFFF',
            ],
            $template->default_config ?? []
        );
    }
}