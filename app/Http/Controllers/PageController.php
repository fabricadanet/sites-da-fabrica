<?php

namespace App\Http\Controllers;

use App\Models\Site;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Listar páginas de um site
     */
    public function index(Site $site)
    {
        $this->authorize('view', $site);

        $pages = $site->pages()->orderBy('order')->paginate(20);

        return view('tenant.pages.index', compact('site', 'pages'));
    }

    /**
     * Editar página
     */
    public function edit(Site $site, Page $page)
    {
        $this->authorize('view', $site);

        if ($page->site_id !== $site->id) {
            abort(404);
        }

        $page->load('sections');

        return view('tenant.pages.edit', compact('site', 'page'));
    }

    /**
     * Criar nova página
     */
    public function store(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        // Gerar slug a partir do título
        $slug = Str::slug($validated['title']);
        
        // Garantir que é único dentro do site
        $count = $site->pages()->where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $page = $site->pages()->create([
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'] ?? '',
            'is_published' => false,
            'is_visible_in_menu' => true,
        ]);

        return response()->json([
            'message' => 'Página criada com sucesso!',
            'page' => $page,
            'redirect' => route('tenant.pages.edit', [$site->slug, $page->slug]),
        ]);
    }

    /**
     * Atualizar página
     */
    public function update(Request $request, Site $site, Page $page)
    {
        $this->authorize('update', $site);

        if ($page->site_id !== $site->id) {
            abort(404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'meta_description' => 'nullable|string|max:160',
            'is_visible_in_menu' => 'boolean',
            'is_published' => 'boolean',
            'seo_title' => 'nullable|string|max:70',
            'seo_keywords' => 'nullable|string|max:160',
            'order' => 'integer|min:0',
        ]);

        // Se é a home, não permitir mudar slug
        if (!$page->is_home && isset($validated['title'])) {
            $slug = Str::slug($validated['title']);
            $validated['slug'] = $slug;
        }

        $page->update($validated);

        return response()->json([
            'message' => 'Página atualizada com sucesso!',
            'page' => $page,
        ]);
    }

    /**
     * Deletar página
     */
    public function destroy(Site $site, Page $page)
    {
        $this->authorize('update', $site);

        if ($page->site_id !== $site->id) {
            abort(404);
        }

        // Não permitir deletar página home
        if ($page->is_home) {
            return response()->json([
                'message' => 'Não é possível deletar a página inicial',
            ], 422);
        }

        $pageName = $page->title;
        $page->delete();

        return response()->json([
            'message' => "Página '{$pageName}' deletada com sucesso!",
        ]);
    }

    /**
     * Publicar página
     */
    public function publish(Site $site, Page $page)
    {
        $this->authorize('update', $site);

        if ($page->site_id !== $site->id) {
            abort(404);
        }

        $page->publish();

        return response()->json([
            'message' => 'Página publicada!',
        ]);
    }

    /**
     * Despublicar página
     */
    public function unpublish(Site $site, Page $page)
    {
        $this->authorize('update', $site);

        if ($page->site_id !== $site->id) {
            abort(404);
        }

        $page->unpublish();

        return response()->json([
            'message' => 'Página despublicada!',
        ]);
    }

    /**
     * Reordenar páginas
     */
    public function reorder(Request $request, Site $site)
    {
        $this->authorize('update', $site);

        $validated = $request->validate([
            'pages' => 'array',
            'pages.*' => 'integer',
        ]);

        foreach ($validated['pages'] as $order => $pageId) {
            $site->pages()
                ->where('id', $pageId)
                ->update(['order' => $order]);
        }

        return response()->json([
            'message' => 'Ordem das páginas atualizada!',
        ]);
    }

    /**
     * Duplicar página
     */
    public function duplicate(Site $site, Page $page)
    {
        $this->authorize('update', $site);

        if ($page->site_id !== $site->id) {
            abort(404);
        }

        $newPage = $page->replicate();
        $newPage->title = $page->title . ' (Cópia)';
        $newPage->slug = Str::slug($newPage->title);
        $newPage->is_published = false;
        $newPage->save();

        // Duplicar seções
        foreach ($page->sections as $section) {
            $newSection = $section->replicate();
            $newSection->page_id = $newPage->id;
            $newSection->save();
        }

        return response()->json([
            'message' => 'Página duplicada com sucesso!',
            'page' => $newPage,
        ]);
    }
}