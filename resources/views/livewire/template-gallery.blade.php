<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Escolha um Template') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        @if($templates->isEmpty())
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 text-center">
                 <p class="text-gray-600">Nenhum template encontrado.</p>
                 <p class="mt-2 text-sm text-gray-500">
                     (Admin: execute <code class="px-2 py-1 bg-gray-200 rounded-md">php artisan templates:sync</code>)
                 </p>
             </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                
                @foreach ($templates as $template)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden" wire:key="{{ $template->id }}">
                        <a href="#" wire:click.prevent="selectTemplate({{ $template->id }})">
                            <img 
                                src="{{ route('template.asset', ['templateName' => $template->name, 'assetPath' => 'preview.png']) }}" 
                                alt="{{ $template->display_name }}" 
                                class="w-full h-48 object-cover object-top hover:opacity-90 transition-opacity">
                        </a>
                        
                        <div class="p-4">
                            <h3 class="text-md font-semibold text-gray-800">{{ $template->display_name }}</h3>
                            <p class="text-sm text-gray-600 mt-1 h-16 overflow-hidden">
                                {{ $template->description }}
                            </p>
                        </div>
                        
                        <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                            <button 
                                wire:click="selectTemplate({{ $template->id }})"
                                wire:loading.attr="disabled"
                                wire:target="selectTemplate({{ $template->id }})"
                                class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                Escolher
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
