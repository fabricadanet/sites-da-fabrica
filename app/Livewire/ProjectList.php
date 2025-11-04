<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Collection;

class ProjectList extends Component
{
    public Collection $projects;

    public function mount()
    {
        $this->loadProjects();
    }

    #[On('projectUpdated')] // Escuta evento para recarregar
    public function loadProjects()
    {
        $this->projects = Project::where('user_id', auth()->id())
            ->with('template') 
            ->latest()
            ->get();
    }

    public function render()
    {
        // Aponta para a view do seu repositório
        return view('user.dashboard')
            ->layout('layouts.app'); // Usa seu layout existente
    }
}