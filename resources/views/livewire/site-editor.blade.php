<div x-data="{
    showToast: false,
    toastMessage: '',
    notify(message) {
        this.toastMessage = message;
        this.showToast = true;
        setTimeout(() => this.showToast = false, 3000);
    }
}" @notify.window="notify($event.detail)">

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editando: {{ $project->name }}
            </h2>
            <div>
                <button wire:click="save" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md text-sm font-medium">
                    Salvar Rascunho
                </button>
                <button wire:click="openPublishModal" class="ml-2 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md text-sm font-medium">
                    Publicar
                </button>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 h-screen">

        <div class="md:col-span-1 p-4 bg-gray-50 overflow-y-auto">
            <form wire:submit.prevent="save">
                <div class="space-y-4">
                    
                    @foreach ($schema['fields'] ?? [] as $field)
                        <div wire:key="{{ $field['name'] }}">
                            <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700">
                                {{ $field['label'] }}
                            </label>
                            
                            @if ($field['type'] === 'text')
                                <input type="text" id="{{ $field['name'] }}" 
                                       wire:model.live.debounce.300ms="formData.{{ $field['name'] }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            
                            @elseif ($field['type'] === 'textarea')
                                <textarea id="{{ $field['name'] }}" rows="4"
                                          wire:model.live.debounce.300ms="formData.{{ $field['name'] }}"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            
                            @elseif ($field['type'] === 'color')
                                <input type="color" id="{{ $field['name'] }}" 
                                       wire:model.live.debounce.300ms="formData.{{ $field['name'] }}"
                                       class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm">

                            @elseif ($field['type'] === 'image_upload')
                                <input type="text" id="{{ $field['name'] }}" 
                                       wire:model.live.debounce.300ms="formData.{{ $field['name'] }}"
                                       placeholder="https://url-da-sua-imagem.com/imagem.png"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <span class="text-xs text-gray-500">Cole a URL de uma imagem hospedada.</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </form>
        </div>

        <div class="md:col-span-2 p-4 overflow-y-auto">
            <div class="shadow-lg rounded-lg overflow-hidden" wire:ignore>
                <iframe 
                    class="w-full h-full min-h-[80vh] border-0" 
                    :srcdoc="$wire.get('previewHtml')"
                    x-data 
                    x-init="$watch('$wire.previewHtml', value => $el.srcdoc = value)">
                </iframe>
            </div>
        </div>
    </div>

    <x-dialog-modal wire:model.live="showDomainModal">
        <x-slot name="title">
            Publicar Site
        </x-slot>

        <x-slot name="content">
            <p class="mb-4">Escolha o endereço do seu site. Você só pode usar uma opção.</p>
            
            <div class="mb-4">
                <label for="subdomain" class="block text-sm font-medium text-gray-700">Subdomínio (Grátis)</label>
                <div class="flex rounded-md shadow-sm">
                    <input type="text" wire:model="subdomain" id="subdomain" class="flex-1 block w-full rounded-none rounded-l-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" placeholder="meu-negocio">
                    <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                        .sitesdafabrica.com.br
                    </span>
                </div>
            </div>

            <div class="text-center my-2 text-sm text-gray-500">OU</div>

            <div class="mb-4">
                <label for="customDomain" class="block text-sm font-medium text-gray-700">Domínio Customizado (Plano Pro)</label>
                <input type="text" wire:model="customDomain" id="customDomain" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="www.meudominio.com">
                <span class="text-xs text-gray-500 mt-1">Você deve apontar seu DNS (CNAME ou A) para nosso servidor.</span>
            </div>
            
            <x-input-error for="domain" class="mt-2" />
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('showDomainModal', false)">
                Cancelar
            </x-secondary-button>

            <x-button class="ml-2" wire:click="publish" wire:loading.attr="disabled">
                Publicar Agora
            </x-button>
        </x-slot>
    </x-dialog-modal>
    
    <div x-show="showToast" x-transition
         style="display: none;"
         class="fixed bottom-5 right-5 px-4 py-2 bg-green-600 text-white rounded-lg shadow-lg">
        <span x-text="toastMessage"></span>
    </div>
</div>
