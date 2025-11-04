<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Template;
use App\Models\Project;
use Illuminate\Support\Collection;

class TemplateGallery extends Component
{
    public Collection $templates;

    public function mount()
    {
        $this->templates = Template::where('status', 'active')->latest()->get();
    }

    public function selectTemplate($templateId)
    {
        $template = Template::findOrFail($templateId);

        $project = Project::create([
            'user_id' => auth()->id(),
            'template_id' => $template->id,
            'name' => "Novo Site ({$template->display_name})",
            'status' => 'draft',
            'json_data' => $this->getDefaultsFromSchema($template->schema),
        ]);

        return $this->redirectRoute('projects.edit', ['project' => $project]);
    }

/**
     * Helper para preencher o formulário com os valores padrão
     * @param array|null $schema O schema do template (agora aceita nulo)
     * @return array
     */
    private function getDefaultsFromSchema(?array $schema): array
    {
        // Se o schema for nulo ou não tiver campos, retorna um array vazio.
        if (is_null($schema) || empty($schema['fields'])) {
            return [];
        }

        // Se for válido, continua...
        return collect($schema['fields'])
            // ----> ADICIONE ESTE FILTRO <----
            // Ignora campos que não são de input (como 'heading')
            ->where('type', '!=', 'heading') 
            // -------------------------------
            ->mapWithKeys(fn ($field) => [$field['name'] => $field['default'] ?? ''])
            ->all();
    }

    public function render()
    {
        // Aponta para a view que refatoramos do seu grid-templates
        return view('livewire.template-gallery')
            ->layout('layouts.app');
    }
}
