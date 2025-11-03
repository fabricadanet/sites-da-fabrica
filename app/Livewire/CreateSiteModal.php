<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Template;
use App\Models\Site;
use App\Models\Plan;
use Illuminate\Support\Str;

class CreateSiteModal extends Component
{
    public $step = 1; // 1: Templates, 2: Config, 3: Review
    public $templates = [];
    public $selectedTemplateId = null;
    public $siteName = '';
    public $siteSlug = '';
    public $autoGenerateSlug = true;
    public $isOpen = false;
    public $subscription = null;
    public $error = '';

    #[Computed]
    public function selectedTemplate()
    {
        return $this->selectedTemplateId 
            ? Template::find($this->selectedTemplateId)
            : null;
    }

    public function mount()
    {
        $this->templates = Template::active()->ordered()->get();
        $this->subscription = auth()->user()?->subscriptions()->active()->first();
    }

    public function openModal()
    {
        // Validar limite de sites
        $maxSites = $this->subscription?->plan?->max_sites ?? 1;
        $currentSites = auth()->user()->sites()->count();

        if ($currentSites >= $maxSites) {
            $this->error = "Você atingiu o limite de {$maxSites} site(s) no seu plano.";
            return;
        }

        $this->isOpen = true;
        $this->reset(['step', 'siteName', 'siteSlug', 'selectedTemplateId', 'error']);
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset();
    }

    public function selectTemplate($templateId)
    {
        $this->selectedTemplateId = $templateId;
        $this->step = 2;
    }

    public function updatedSiteName($value)
    {
        if ($this->autoGenerateSlug) {
            $this->siteSlug = Str::slug($value);
        }
    }

    public function toggleAutoSlug()
    {
        $this->autoGenerateSlug = !$this->autoGenerateSlug;
        
        if ($this->autoGenerateSlug) {
            $this->siteSlug = Str::slug($this->siteName);
        }
    }

    public function validateSlug()
    {
        // Validar se o slug é único
        $exists = Site::where('slug', $this->siteSlug)
            ->whereNull('deleted_at')
            ->exists();

        if ($exists) {
            $this->addError('siteSlug', 'Este slug já está em uso. Escolha outro.');
            return false;
        }

        return true;
    }

    public function goToReview()
    {
        $this->validate([
            'siteName' => 'required|string|min:3|max:100',
            'siteSlug' => 'required|string|min:3|max:50|regex:/^[a-z0-9-]+$/',
            'selectedTemplateId' => 'required|exists:templates,id',
        ], [
            'siteName.required' => 'Digite um nome para seu site',
            'siteName.min' => 'O nome deve ter pelo menos 3 caracteres',
            'siteSlug.required' => 'O slug é obrigatório',
            'siteSlug.regex' => 'O slug pode conter apenas letras, números e hífens',
            'selectedTemplateId.required' => 'Selecione um template',
        ]);

        if (!$this->validateSlug()) {
            return;
        }

        $this->step = 3;
    }

    public function createSite()
    {
        try {
            $site = Site::create([
                'user_id' => auth()->id(),
                'template_id' => $this->selectedTemplateId,
                'subscription_id' => $this->subscription?->id,
                'name' => $this->siteName,
                'slug' => $this->siteSlug,
                'subdomain' => $this->siteSlug,
                'config' => [
                    'title' => $this->siteName,
                    'description' => 'Seu site profissional',
                    'primary_color' => '#3B82F6',
                ],
                'status' => 'draft',
                'is_published' => false,
            ]);

            // Criar página home padrão
            $site->pages()->create([
                'title' => 'Home',
                'slug' => '/',
                'is_home' => true,
                'is_published' => true,
                'is_visible_in_menu' => true,
            ]);

            $this->dispatch('site-created', siteId: $site->id);
            $this->closeModal();

            // Redirecionar para editor
            return redirect()->route('tenant.sites.edit', $site->slug);

        } catch (\Exception $e) {
            $this->error = 'Erro ao criar site: ' . $e->getMessage();
            \Log::error('Create site error', ['error' => $e->getMessage()]);
        }
    }

    public function goBack()
    {
        $this->step = max(1, $this->step - 1);
    }

    public function render()
    {
        return view('livewire.create-site-modal', [
            'templates' => $this->templates,
            'selectedTemplate' => $this->selectedTemplate(),
            'subscription' => $this->subscription,
        ]);
    }
}