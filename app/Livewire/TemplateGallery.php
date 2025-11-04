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

    private function getDefaultsFromSchema(array $schema): array
    {
        return collect($schema['fields'] ?? [])
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
