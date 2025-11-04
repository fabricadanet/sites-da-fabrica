<?php

namespace App\Livewire;

use App\Jobs\PublishSiteJob;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Support\Facades\Blade;
use Livewire\Attributes\On;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed; 

class SiteEditor extends Component
{
    public Project $project;
    public array $schema = [];
    public array $formData = [];

    // Esta é a nossa propriedade de preview
    public string $previewContent = '';

    // Para o modal de publicação
    public bool $showDomainModal = false;
    public ?string $subdomain = null;
    public ?string $customDomain = null;

    public function mount(Project $project)
    {
        if ($project->user_id !== auth()->id()) {
            abort(403);
        }
        
        $this->project = $project;
        $this->project->load('template');
        $this->schema = $this->project->template->schema;
        $this->formData = $this->project->json_data ?? $this->getDefaultsFromSchema();
        
        $this->subdomain = Str::before($this->project->subdomain, '.'); 
        $this->customDomain = $this->project->custom_domain;
        
        // Renderiza o preview inicial
        $this->renderPreview();
    }
    
    /**
     * Hook do Livewire. Chamado quando 'formData' é atualizado.
     */
    public function updated($propertyName)
    {
        if (Str::startsWith($propertyName, 'formData.')) {
            $this->renderPreview();
        }
    }
    
    /**
     * Função helper privada para renderizar o preview.
     */
    private function renderPreview()
    {
        try {
            $templateName = $this->project->template->name;
            $templatePath = storage_path("app/templates/{$templateName}/index.blade.php");

            if (!file_exists($templatePath)) {
                $this->previewContent = "Erro: Template não encontrado. (Sync necessário)";
                return;
            }
            
            $bladeContent = file_get_contents($templatePath);
            $html = Blade::render($bladeContent, $this->formData); 

            // ===================================
            // INÍCIO DA CORREÇÃO (O Erro Atual)
            // ===================================
            
            // Não usamos mais route() aqui. Construímos a URL base manualmente.
            // A rota de assets é: /template-assets/{templateName}/{assetPath}
            // O {assetPath} começa com "assets/...", então o prefixo é:
            
            $baseUrl = "/template-assets/" . $templateName; // Ex: /template-assets/empresa-multiseccao

            // O str_replace vai transformar:
            // href="assets/style.css"
            // EM:
            // href="/template-assets/empresa-multiseccao/assets/style.css"
            // O que corresponde perfeitamente à nossa rota de assets.
            
            $html = str_replace(
                ['href="assets/', 'src="assets/', 'href="./assets/', 'src="./assets/'],
                [
                    'href="' . $baseUrl . '/assets/', 
                    'src="' . $baseUrl . '/assets/',
                    'href="' . $baseUrl . '/assets/', // para ./assets/
                    'src="' . $baseUrl . '/assets/', // para ./assets/
                ],
                $html
            );
            
            // ===================================
            // FIM DA CORREÇÃO
            // ===================================
            
            $this->previewContent = $html;

        } catch (\Exception $e) {
            // Captura o erro que você está vendo e o exibe no preview
            $this->previewContent = "Erro ao renderizar: " . $e->getMessage();
        }
    }


    public function save()
    {
        $this->project->update(['json_data' => $this->formData]);
        $this->dispatch('projectUpdated'); 
        $this->dispatch('notify', 'Salvo com sucesso!'); 
    }
    
    public function openPublishModal()
    {
        $this->save();
        $this->showDomainModal = true;
    }

    public function publish()
    {
        if (!empty($this->subdomain)) {
            $this->customDomain = null;
        } else {
            $this->subdomain = null;
        }
        
        if (empty($this-S>subdomain) && empty($this->customDomain)) {
             $this->addError('domain', 'Você deve preencher um subdomínio ou um domínio customizado.');
             return;
        }
        
        if (!empty($this->subdomain)) {
            $this->project->subdomain = $this->subdomain . '.' . config('services.cpanel.main_domain');
            $this->project->custom_domain = null;
        } else {
            $this->project->subdomain = null; 
            $this->project->custom_domain = $this->customDomain; 
        }

        $this->project->save();
        $this->project->update(['status' => 'pending']); 
        
        PublishSiteJob::dispatch($this->project);
        
        $this->showDomainModal = false;
        
        $this->dispatch('projectUpdated'); 
        $this->dispatch('notify', 'Publicação iniciada! Seu site estará no ar em alguns minutos.'); 
    }

    private function getDefaultsFromSchema(?array $schema = null): array
    {
        $schemaToUse = $schema ?? $this->schema; // <--- CORRIGIDO (sem this_>)

        if (is_null($schemaToUse) || empty($schemaToUse['fields'])) {
            return [];
        }

        return collect($schemaToUse['fields'])
            ->where('type', '!=', 'heading')
            ->filter(fn ($field) => isset($field['name'])) 
            ->mapWithKeys(fn ($field) => [$field['name'] => $field['default'] ?? ''])
            ->all();
    }

    public function render()
    {
        return view('livewire.site-editor')
             ->layout('layouts.app');
    }
}