<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Meus Sites') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 text-right">
            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                Criar Novo Site
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                
                @if ($projects->isEmpty())
                    <p class="text-gray-600">
                        Você ainda não criou nenhum site. 
                        <a href="{{ route('projects.create') }}" class="text-blue-600 hover:underline">Vamos começar?</a>
                    </p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach ($projects as $project)
                            <li class="py-4 flex items-center justify-between" wire:key="{{ $project->id }}">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ $project->name }}</h3>
                                    <p class="text-sm text-gray-500">
                                        Template: {{ $project->template->display_name ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        @if($project->status === 'published')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Publicado
                                            </span>
                                            <a href="https://{{ $project->getDeploymentHost() }}" target="_blank" class="ml-2 text-blue-600 hover:underline">
                                                {{ $project->getDeploymentHost() }} &rarr;
                                            </a>
                                        @elseif($project->status === 'failed')
                                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Falha na Publicação
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-3 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800">
                                        Editar
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>