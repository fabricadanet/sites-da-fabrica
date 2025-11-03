<div>
    <!-- Button to Open Modal -->
    <button 
        wire:click="openModal"
        class="btn-primary text-sm w-full sm:w-auto"
    >
        <i class="fas fa-rocket mr-2"></i> Criar Site Agora 
    </button>

    <!-- Modal Backdrop -->
    @if($isOpen)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity" 
             wire:click="closeModal"
             x-transition>
        </div>
    @endif

    <!-- Modal -->
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 {{ !$isOpen ? 'hidden' : '' }}">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto" 
             @click.stop>

            <!-- Header -->
            <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6 flex items-center justify-between border-b">
                <div>
                    <h2 class="text-2xl font-bold">
                        @if($step === 1)
                            Escolha um Template
                        @elseif($step === 2)
                            Configure Seu Site
                        @else
                            Revisar e Criar
                        @endif
                    </h2>
                    <p class="text-blue-100 text-sm mt-1">Passo {{ $step }} de 3</p>
                </div>
                <button wire:click="closeModal" class="text-blue-100 hover:text-white transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Progress Bar -->
            <div class="bg-gray-200 h-1">
                <div class="bg-blue-600 h-1 transition-all duration-300" 
                     style="width: {{ ($step / 3) * 100 }}%">
                </div>
            </div>

            <!-- Error Message -->
            @if($error)
                <div class="m-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-600 mt-0.5"></i>
                    <div>
                        <h4 class="font-semibold text-red-900">Erro</h4>
                        <p class="text-red-700 text-sm">{{ $error }}</p>
                    </div>
                </div>
            @endif

            <!-- Content -->
            <div class="p-6 md:p-8">

                <!-- Step 1: Templates -->
                @if($step === 1)
                    <div class="space-y-4">
                        <p class="text-gray-600 mb-6">
                            Escolha o template que melhor se encaixa no seu negócio. Você poderá customizar tudo depois.
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse($templates as $template)
                                <div 
                                    wire:click="selectTemplate({{ $template->id }})"
                                    class="cursor-pointer group relative bg-white border-2 border-gray-200 rounded-xl overflow-hidden hover:border-blue-500 transition-all duration-300 transform hover:scale-105"
                                >
                                    <!-- Thumbnail -->
                                    <div class="relative h-48 bg-gray-100 flex items-center justify-center overflow-hidden">
                                        @if($template->thumbnail_url)
                                            <img 
                                                src="{{ $template->thumbnail_url }}" 
                                                alt="{{ $template->name }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                            >
                                        @else
                                            <div class="text-center text-gray-400">
                                                <i class="fas fa-image text-4xl mb-2"></i>
                                                <p class="text-sm">Sem preview</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Content -->
                                    <div class="p-4">
                                        <h4 class="font-bold text-gray-900 mb-1">{{ $template->name }}</h4>
                                        <p class="text-xs text-gray-500 mb-3">{{ $template->description }}</p>
                                        
                                        <div class="flex items-center justify-between">
                                            <span class="inline-block bg-blue-50 text-blue-700 text-xs px-3 py-1 rounded-full font-medium">
                                                {{ $template->category }}
                                            </span>
                                            <i class="fas fa-arrow-right text-blue-600 group-hover:translate-x-1 transition-transform"></i>
                                        </div>
                                    </div>

                                    <!-- Overlay on Hover -->
                                    <div class="absolute inset-0 bg-blue-600 bg-opacity-0 group-hover:bg-opacity-10 transition-all"></div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-12">
                                    <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">Nenhum template disponível</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                <!-- Step 2: Configuration -->
                @elseif($step === 2)
                    <div class="space-y-6 max-w-2xl">
                        <!-- Selected Template Preview -->
                        @if($selectedTemplate)
                            <div class="bg-gradient-to-r from-blue-50 to-purple-50 p-6 rounded-xl border border-blue-100">
                                <div class="flex items-center gap-4">
                                    @if($selectedTemplate->thumbnail_url)
                                        <img 
                                            src="{{ $selectedTemplate->thumbnail_url }}" 
                                            alt="{{ $selectedTemplate->name }}"
                                            class="w-24 h-24 rounded-lg object-cover"
                                        >
                                    @endif
                                    <div>
                                        <h4 class="font-bold text-gray-900 mb-1">{{ $selectedTemplate->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $selectedTemplate->description }}</p>
                                        <button 
                                            wire:click="goBack"
                                            class="text-sm text-blue-600 hover:text-blue-700 mt-2 font-medium"
                                        >
                                            <i class="fas fa-exchange"></i> Mudar Template
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Site Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                <i class="fas fa-heading mr-2 text-blue-600"></i> Nome do Site
                            </label>
                            <input 
                                type="text"
                                wire:model.live="siteName"
                                placeholder="Ex: Consultoria Silva"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            >
                            @error('siteName')
                                <p class="text-red-600 text-sm mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">Este nome aparecerá no seu site</p>
                        </div>

                        <!-- Site Slug -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-semibold text-gray-900">
                                    <i class="fas fa-link mr-2 text-blue-600"></i> URL do Site
                                </label>
                                <button 
                                    wire:click="toggleAutoSlug"
                                    class="text-xs text-blue-600 hover:text-blue-700 font-medium transition"
                                >
                                    {{ $autoGenerateSlug ? '🔒 Auto' : '✏️ Manual' }}
                                </button>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 relative">
                                    <input 
                                        type="text"
                                        wire:model.live="siteSlug"
                                        :disabled="$autoGenerateSlug"
                                        placeholder="seu-site"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition disabled:bg-gray-100 disabled:text-gray-500"
                                    >
                                </div>
                                <span class="text-gray-600 font-medium">.sitesdafabrica.com.br</span>
                            </div>
                            @error('siteSlug')
                                <p class="text-red-600 text-sm mt-2"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-2">URL para acessar seu site (apenas letras, números e hífens)</p>
                        </div>

                        <!-- Subscription Info -->
                        @if($subscription)
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                                <div class="flex items-start gap-3">
                                    <i class="fas fa-check-circle text-blue-600 mt-1"></i>
                                    <div>
                                        <p class="text-sm text-gray-900 font-medium">Plano: <strong>{{ $subscription->plan->name }}</strong></p>
                                        <p class="text-xs text-gray-600 mt-1">
                                            Você pode criar até <strong>{{ $subscription->plan->max_sites }}</strong> site(s) neste plano
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                <p class="text-sm text-yellow-900">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Você está usando o plano grátis (1 site). 
                                    <a href="#" class="font-bold underline">Upgrade para criar mais sites</a>
                                </p>
                            </div>
                        @endif
                    </div>

                <!-- Step 3: Review -->
                @else
                    <div class="max-w-2xl space-y-6">
                        <p class="text-gray-600">
                            Confirme as informações do seu novo site antes de criar:
                        </p>

                        <!-- Review Card -->
                        <div class="border-2 border-gray-200 rounded-xl overflow-hidden">
                            <!-- Template Preview -->
                            @if($selectedTemplate?->thumbnail_url)
                                <div class="h-48 bg-gray-100 overflow-hidden">
                                    <img 
                                        src="{{ $selectedTemplate->thumbnail_url }}" 
                                        alt="{{ $selectedTemplate->name }}"
                                        class="w-full h-full object-cover"
                                    >
                                </div>
                            @endif

                            <!-- Review Details -->
                            <div class="p-6 space-y-4">
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-xs text-gray-600 uppercase font-bold mb-1">Template</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $selectedTemplate?->name }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-600 uppercase font-bold mb-1">Nome do Site</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $siteName }}</p>
                                    </div>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-600 uppercase font-bold mb-1">URL do Site</p>
                                    <p class="text-lg font-bold text-blue-600">
                                        {{ $siteSlug }}.sitesdafabrica.com.br
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-600 uppercase font-bold mb-1">Descrição</p>
                                    <p class="text-gray-700">{{ $selectedTemplate?->description }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Checklist -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-3 text-green-700">
                                <i class="fas fa-check-circle"></i>
                                <span>Você poderá customizar tudo depois de criar o site</span>
                            </div>
                            <div class="flex items-center gap-3 text-green-700">
                                <i class="fas fa-check-circle"></i>
                                <span>Seu site começará como rascunho (não será publicado automaticamente)</span>
                            </div>
                            <div class="flex items-center gap-3 text-green-700">
                                <i class="fas fa-check-circle"></i>
                                <span>Você pode criar até {{ $subscription?->plan?->max_sites ?? 1 }} site(s) neste plano</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Footer Actions -->
            <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 md:px-8 py-4 flex items-center justify-between">
                <button 
                    wire:click="closeModal"
                    class="px-6 py-2 text-gray-700 font-medium hover:bg-gray-100 rounded-lg transition"
                >
                    Cancelar
                </button>

                <div class="flex items-center gap-3">
                    @if($step > 1)
                        <button 
                            wire:click="goBack"
                            class="px-6 py-2 border border-gray-300 text-gray-700 font-medium hover:bg-gray-100 rounded-lg transition"
                        >
                            <i class="fas fa-arrow-left mr-2"></i> Voltar
                        </button>
                    @endif

                    @if($step < 3)
                        <button 
                            wire:click="goToReview"
                            class="px-6 py-2 bg-blue-600 text-white font-medium hover:bg-blue-700 rounded-lg transition"
                        >
                            Próximo <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    @else
                        <button 
                            wire:click="createSite"
                            wire:loading.attr="disabled"
                            class="px-8 py-2 bg-green-600 text-white font-bold hover:bg-green-700 rounded-lg transition disabled:opacity-50"
                        >
                            <span wire:loading.remove>
                                <i class="fas fa-check mr-2"></i> Criar Site
                            </span>
                            <span wire:loading>
                                <i class="fas fa-spinner animate-spin mr-2"></i> Criando...
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>