<?php

namespace App\Livewire;

use App\Jobs\PublishSiteJob;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Support\Facades\Blade;

class SiteEditor extends Component
{
    public Project $project;
    public array $schema = [];
    public array $formData = [];

    // Para o modal de publicação
    public bool $showDomainModal = false;
    public ?string $subdomain = null;
    public ?string $customDomain = null;

    public function mount(Project $project)
    {
        // Garante que o usuário só edite seus próprios projetos
        if ($project->user_id !== auth()->id()) {
            abort(403);
        }
        
        $this->project = $project;
        $this->project->load('template');
        $this->schema = $this->project->template->schema;
        $this->formData = $this->project->json_data ?? $this->getDefaultsFromSchema();
        
        // Preenche o modal
        $this->subdomain = $this->project->subdomain;
        $this->customDomain = $this->project->custom_domain;
    }

    // Computed Property para o Live Preview
    public function getPreviewHtmlProperty(): string
    {
        try {
            $templateName = $this->project->template->name;
            $templatePath = storage_path("app/templates/{$templateName}/index.blade.php");

            if (!file_exists($templatePath)) {
                return "Erro: Template não encontrado. (Sync necessário)";
            }
            
            $bladeContent = file_get_contents($templatePath);
            $html = Blade::render($bladeContent, $this->formData);

            // Injeta o caminho dos assets
            $assetBaseUrl = rtrim(route('template.asset', ['templateName' => $templateName, 'assetPath' => '']), '/');
            $html = str_replace(
                ['href="assets/', 'src="assets/', 'href="./assets/', 'src="./assets/'],
                [
                    'href="' . $assetBaseUrl . '/assets/', 
                    'src="' . $assetBaseUrl . '/assets/',
                    'href="' . $assetBaseUrl . '/assets/',
                    'src="' . $assetBaseUrl . '/assets/',
                ],
                $html
            );
            return $html;
        } catch (\Exception $e) {
            return "Erro ao renderizar: " . $e->getMessage();
        }
    }

    public function save()
    {
        $this->project->update(['json_data' => $this->formData]);
        $this->dispatch('projectUpdated'); // Avisa outros componentes (ex: ProjectList)
        $this->dispatch('notify', 'Salvo com sucesso!');
    }
    
    public function openPublishModal()
    {
        $this->save();
        $this->showDomainModal = true;
    }

    public function publish()
    {
        // Limpa os dados opostos
        if (!empty($this->subdomain)) {
            $this->customDomain = null;
        } else {
            $this->subdomain = null;
        }

        // Validação
        if (empty($this->subdomain) && empty($this->customDomain)) {
             $this->addError('domain', 'Você deve preencher um subdomínio ou um domínio customizado.');
             return;
        }
        
        // Salva os dados no projeto
        if (!empty($this->subdomain)) {
            // O usuário digitou 'meu-negocio'
            // Nós salvamos o FQDN (Full Qualified Domain Name)
            $this->project->subdomain = $this->subdomain . '.' . config('services.cpanel.main_domain');
            $this->project->custom_domain = null;
        } else {
            // O usuário digitou 'www.meusite.com'
            $this->project->subdomain = null;
            $this->project->custom_domain = $this->customDomain;
        }

        $this->project->save();
        $this->project->update(['status' => 'pending']);
        
        // Dispara o NOVO Job
        PublishSiteJob::dispatch($this->project);
        
        $this->showDomainModal = false;
        $this->dispatch('projectUpdated'); // Avisa o ProjectList para atualizar
        $this->dispatch('notify', 'Publicação iniciada! Seu site estará no ar em alguns minutos.');
    }

    private function getDefaultsFromSchema(): array
    {
        return collect($this->schema['fields'] ?? [])
            ->mapWithKeys(fn ($field) => [$field['name'] => $field['default'] ?? ''])
            ->all();
    }

    public function render()
    {
        return view('livewire.site-editor')
             ->layout('layouts.app');
    }
}
