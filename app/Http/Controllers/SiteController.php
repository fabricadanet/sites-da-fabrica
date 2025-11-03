<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Page;
use App\Models\Section;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Listar todos os sites do usuário
     */
    public function index()
    {
        $sites = auth()->user()->sites()
            ->with('template', 'subscription.plan')
            ->latest()
            ->paginate(12);

        return view('tenant.sites.index', compact('sites'));
    }

    /**
     * Editar um site específico
     */
    public function edit(Site $site)
    {
        // Autorizar acesso
        $this->authorize('update', $site);

        $site->load('pages', 'template', 'subscription.plan');

        return view('tenant.sites.edit', compact('site'));
    }

    /**
     * Atualizar configurações do site
     */
    public function update(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'config.title' => 'nullable|string|max:100',
            'config.description' => 'nullable|string|max:500',
            'config.primary_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'config.secondary_color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
        ]);

        // Mesclar config
        $config = $site->config ?? [];
        if (isset($validated['config'])) {
            $config = array_merge($config, $validated['config']);
            unset($validated['config']);
        }

        $site->update(array_merge($validated, ['config' => $config]));

        return response()->json([
            'message' => 'Site atualizado com sucesso!',
            'site' => $site,
        ]);
    }

    /**
     * Publicar um site
     */
    public function publish(Site $site)
    {
        $this->authorize('update', $site);

        // Validar se tem página home
        $homePage = $site->pages()->where('is_home', true)->first();
        if (!$homePage) {
            return response()->json([
                'message' => 'Você precisa ter uma página inicial antes de publicar',
            ], 422);
        }

        // Validar se tem conteúdo
        if (!$homePage->content && !$homePage->sections()->exists()) {
            return response()->json([
                'message' => 'Sua página inicial está vazia. Adicione conteúdo antes de publicar',
            ], 422);
        }

        $site->publish();

        return response()->json([
            'message' => 'Site publicado com sucesso!',
            'site' => $site,
        ]);
    }

    /**
     * Despublicar um site
     */
    public function unpublish(Site $site)
    {
        $this->authorize('update', $site);

        $site->unpublish();

        return response()->json([
            'message' => 'Site despublicado com sucesso!',
        ]);
    }

    /**
     * Deletar um site
     */
    public function destroy(Site $site)
    {
        $this->authorize('delete', $site);

        $siteName = $site->name;
        $site->delete();

        return response()->json([
            'message' => "Site '{$siteName}' deletado com sucesso!",
        ]);
    }

    /**
     * Duplicar um site
     */
    public function duplicate(Site $site)
    {
        $this->authorize('view', $site);

        $subscription = auth()->user()->subscriptions()->active()->first();
        $maxSites = $subscription?->plan?->max_sites ?? 1;
        $currentSites = auth()->user()->sites()->count();

        if ($currentSites >= $maxSites) {
            return response()->json([
                'message' => "Você atingiu o limite de {$maxSites} site(s) no seu plano",
            ], 422);
        }

        $newSite = $site->replicate();
        $newSite->name = $site->name . ' (Cópia)';
        $newSite->slug = \Illuminate\Support\Str::slug($newSite->name) . '-' . \Illuminate\Support\Str::random(5);
        $newSite->subdomain = $newSite->slug;
        $newSite->custom_domain = null;
        $newSite->use_custom_domain = false;
        $newSite->is_published = false;
        $newSite->save();

        // Duplicar páginas e seções
        foreach ($site->pages as $page) {
            $newPage = $page->replicate();
            $newPage->site_id = $newSite->id;
            $newPage->is_published = false;
            $newPage->save();

            // Duplicar seções
            foreach ($page->sections as $section) {
                $newSection = $section->replicate();
                $newSection->page_id = $newPage->id;
                $newSection->save();
            }
        }

        return response()->json([
            'message' => 'Site duplicado com sucesso!',
            'site' => $newSite->load('pages', 'template'),
        ]);
    }

    /**
     * Preview de um site
     */
    public function preview(Site $site)
    {
        $this->authorize('view', $site);

        $site->load('pages', 'template');
        $homePage = $site->getHomePage();

        return view('tenant.sites.preview', compact('site', 'homePage'));
    }

    /**
     * Estatísticas de um site
     */
    public function stats(Site $site)
    {
        $this->authorize('view', $site);

        $stats = [
            'views' => $site->total_views,
            'visitors' => $site->unique_visitors,
            'pages' => $site->pages()->count(),
            'published' => $site->pages()->where('is_published', true)->count(),
            'storage_used' => $site->current_storage_used_mb,
            'bandwidth_used' => $site->current_bandwidth_used_gb,
        ];

        // Estatísticas por página
        $pageStats = $site->analytics()
            ->groupBy('page_id')
            ->selectRaw('page_id, COUNT(*) as views')
            ->orderByDesc('views')
            ->limit(10)
            ->with('page')
            ->get();

        return response()->json([
            'stats' => $stats,
            'pageStats' => $pageStats,
        ]);
    }
}