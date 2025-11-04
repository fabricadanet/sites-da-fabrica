<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use App\Models\Template;
use App\Models\Site;
use App\Models\Subscription;
use Illuminate\Support\Str;

class CreateSiteModal extends Component
{
    // State
    public int $step = 1;
    public bool $isOpen = false;
    public string $error = '';
    public bool $isLoading = false;

    // Form data
    #[Validate('required|string|min:3|max:100')]
    public string $siteName = '';

    #[Validate('required|string|min:3|max:50|regex:/^[a-z0-9-]+$/')]
    public string $siteSlug = '';

    #[Validate('required|exists:templates,id')]
    public ?int $selectedTemplateId = null;

    // Settings
    public bool $autoGenerateSlug = true;

    // Collections
    public array $templates = [];

    // Relations
    public ?Subscription $subscription = null;

    /**
     * Computed property para template selecionado
     */
    #[Computed]
    public function selectedTemplate()
    {
        return $this->selectedTemplateId
            ? Template::find($this->selectedTemplateId)
            : null;
    }

    /**
     * Validar se usuário pode criar mais sites
     */
    #[Computed]
    public function canCreateSite(): bool
    {
        $maxSites = $this->subscription?->plan?->max_sites ?? 1;
        $currentSites = auth()->user()?->sites()->count() ?? 0;

        return $currentSites < $maxSites;
    }

    /**
     * Obter mensagem de limite de sites
     */
    #[Computed]
    public function siteCountMessage(): string
    {
        $maxSites = $this->subscription?->plan?->max_sites ?? 1;
        $currentSites = auth()->user()?->sites()->count() ?? 0;

        return "{$currentSites} de {$maxSites} site(s)";
    }

    /**
     * Inicializar componente
     */
    public function mount(): void
    {
        $this->loadTemplates();
        $this->loadSubscription();
    }

    /**
     * Carregar templates ativos
     */
    private function loadTemplates(): void
    {
        $this->templates = Template::active()
            ->ordered()
            ->get()
            ->toArray();
    }

    /**
     * Carregar subscription do usuário
     */
    private function loadSubscription(): void
    {
        $this->subscription = auth()->user()
            ?->subscriptions()
            ->active()
            ->first();
    }

    /**
     * Abrir modal
     */
    public function openModal(): void
    {
        // Validar limite de sites
        if (!$this->canCreateSite) {
            $maxSites = $this->subscription?->plan?->max_sites ?? 1;
            $this->error = "Você atingiu o limite de {$maxSites} site(s) no seu plano.";
            $this->dispatch('site-creation-failed', message: $this->error);
            return;
        }

        $this->reset(['step', 'siteName', 'siteSlug', 'selectedTemplateId', 'error']);
        $this->isOpen = true;
        $this->dispatch('modal-opened');
    }

    /**
     * Fechar modal
     */
    public function closeModal(): void
    {
        $this->isOpen = false;
        $this->reset(['step', 'siteName', 'siteSlug', 'selectedTemplateId', 'error', 'isLoading']);
        $this->dispatch('modal-closed');
    }

    /**
     * Selecionar template
     */
    public function selectTemplate(int $templateId): void
    {
        $this->selectedTemplateId = $templateId;
        $this->step = 2;
    }

    /**
     * Atualizar slug automaticamente quando nome mudar
     */
    #[\Livewire\Attributes\On('update:siteName')]
    public function updatedSiteName(string $value): void
    {
        if ($this->autoGenerateSlug) {
            $this->siteSlug = Str::slug($value);
        }
    }

    /**
     * Alternar entre auto-geração e edição manual do slug
     */
    public function toggleAutoSlug(): void
    {
        $this->autoGenerateSlug = !$this->autoGenerateSlug;

        if ($this->autoGenerateSlug && !empty($this->siteName)) {
            $this->siteSlug = Str::slug($this->siteName);
        }
    }

    /**
     * Validar se slug é único
     */
    private function validateSlugUnique(): bool
    {
        $exists = Site::where('slug', $this->siteSlug)
            ->whereNull('deleted_at')
            ->exists();

        if ($exists) {
            $this->addError('siteSlug', 'Este slug já está em uso. Escolha outro.');
            return false;
        }

        return true;
    }

    /**
     * Ir para página de revisão
     */
    public function goToReview(): void
    {
        try {
            // Validar dados do formulário
            $this->validate();

            // Validar slug único
            if (!$this->validateSlugUnique()) {
                return;
            }

            $this->step = 3;
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->error = 'Por favor, corrija os erros abaixo.';
        }
    }

    /**
     * Voltar para step anterior
     */
    public function goBack(): void
    {
        $this->step = max(1, $this->step - 1);
        $this->error = '';
    }

    /**
     * Criar novo site
     */
    public function createSite(): void
    {
        try {
            $this->isLoading = true;

            // Validação final
            $this->validate();

            if (!$this->validateSlugUnique()) {
                $this->isLoading = false;
                return;
            }

            // Criar site
            $site = Site::create([
                'user_id' => auth()->id(),
                'template_id' => $this->selectedTemplateId,
                'subscription_id' => $this->subscription?->id,
                'name' => $this->siteName,
                'slug' => $this->siteSlug,
                'subdomain' => $this->siteSlug,
                'config' => $this->getDefaultConfig(),
                'status' => 'draft',
                'is_published' => false,
                'meta' => [
                    'created_from_modal' => true,
                    'template_name' => $this->selectedTemplate?->name,
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

            // Disparar eventos
            $this->dispatch('site-created', siteId: $site->id);
            $this->dispatch('notify', message: 'Site criado com sucesso!', type: 'success');

            // Fechar modal
            $this->closeModal();

            // Redirecionar
            $this->redirectRoute('tenant.sites.edit', ['site' => $site->slug], navigate: true);

        } catch (\Exception $e) {
            $this->error = 'Erro ao criar site. Por favor, tente novamente.';
            \Log::error('Create site error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);
            $this->dispatch('site-creation-failed', message: $this->error);
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Obter configuração padrão para novo site
     */
    private function getDefaultConfig(): array
    {
        $template = $this->selectedTemplate;

        return array_merge(
            [
                'title' => $this->siteName,
                'description' => 'Seu site profissional criado com Sites da Fábrica',
                'primary_color' => '#3B82F6',
                'secondary_color' => '#8B5CF6',
                'text_color' => '#1F2937',
                'background_color' => '#FFFFFF',
            ],
            $template?->default_config ?? []
        );
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.create-site-modal', [
            'templates' => $this->templates,
            'selectedTemplate' => $this->selectedTemplate(),
            'subscription' => $this->subscription,
            'canCreateSite' => $this->canCreateSite,
            'siteCountMessage' => $this->siteCountMessage,
        ]);
    }
}