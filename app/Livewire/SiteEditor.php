<?php

namespace App\Livewire;

use App\Jobs\PublishSiteJob;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Support\Facades\Blade;
use Livewire\Attributes\On;
use Illuminate\Support\Str;

class SiteEditor extends Component
{
    public Project $project;
    public array $schema = [];
    public array $formData = [];

    // Propriedade para o preview
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

            // URL Base de Assets
            $baseUrl = "/template-assets/" . $templateName;
            
            $html = str_replace(
                ['href="assets/', 'src="assets/', 'href="./assets/', 'src="./assets/'],
                [
                    'href="' . $baseUrl . '/assets/', 
                    'src="' . $baseUrl . '/assets/',
                    'href="' . $baseUrl . '/assets/',
                    'src="' . $baseUrl . '/assets/',
                ],
                $html
            );
            
            $this->previewContent = $html;

        } catch (\Exception $e) {
            $this->previewContent = "Erro ao renderizar: " . $e->getMessage();
        }
    }


    public function downloadHtml()
    {
        $this->renderPreview();
        $content = $this->previewContent;
        $fileName = Str::slug($this->project->name) . '.html';
        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName);
    }

    public function save()
    {
        $this->renderPreview();
        // Usando o método save() direto, que é mais robusto
        $this->project->json_data = $this->formData;
        $this->project->save();

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
        
        if (empty($this->subdomain) && empty($this->customDomain)) {
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
        $schemaToUse = $schema ?? $this->schema; 

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