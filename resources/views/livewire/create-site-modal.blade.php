<div>
    <!-- Botão para abrir modal -->
    <button
        wire:click="openModal"
        @class([
            'btn-primary text-sm w-full sm:w-auto',
            'opacity-50 cursor-not-allowed' => !$canCreateSite,
        ])
        :disabled="!$canCreateSite"
        title="{{ !$canCreateSite ? 'Limite de sites atingido' : 'Criar novo site' }}"
    >
        <i class="fas fa-rocket mr-2"></i>
        <span>{{ $canCreateSite ? 'Criar Site Agora' : 'Limite Atingido' }}</span>
    </button>

    <!-- Modal Backdrop -->
    @if($isOpen)
        <div
            class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300"
            wire:click="closeModal"
            x-transition
        ></div>
    @endif

    <!-- Modal Principal -->
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 {{ !$isOpen ? 'hidden' : '' }}">
        <div
            class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col transform transition-all"
            @click.stop
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
        >

            <!-- Header Sticky -->
            <div class="sticky top-0 bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6 flex items-center justify-between border-b border-blue-700 z-10">
                <div>
                    <h2 class="text-2xl font-bold flex items-center gap-2">
                        @if($step === 1)
                            <i class="fas fa-image"></i> Escolha um Template
                        @elseif($step === 2)
                            <i class="fas fa-cog"></i> Configure Seu Site
                        @else
                            <i class="fas fa-check-circle"></i> Revisar e Criar
                        @endif
                    </h2>
                    <p class="text-blue-100 text-sm mt-1">Passo <strong>{{ $step }}</strong> de <strong>3</strong></p>
                </div>
                <button
                    wire:click="closeModal"
                    class="text-blue-100 hover:text-white transition-colors hover:scale-110"
                    aria-label="Fechar"
                >
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Progress Bar -->
            <div class="h-1 bg-gray-200">
                <div
                    class="h-full bg-gradient-to-r from-blue-500 to-purple-500 transition-all duration-500 ease-out"
                    style="width: {{ ($step / 3) * 100 }}%"
                ></div>
            </div>

            <!-- Mensagem de Erro Global -->
            @if($error)
                <div class="m-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex items-start gap-4 animate-pulse">
                    <i class="fas fa-exclamation-circle text-red-600 text-xl mt-0.5 flex-shrink-0"></i>
                    <div class="flex-1">
                        <h4 class="font-bold text-red-900">Erro ao processar</h4>
                        <p class="text-red-700 text-sm mt-1">{{ $error }}</p>
                    </div>
                    <button
                        wire:click="$set('error', '')"
                        class="text-red-600 hover:text-red-900 transition"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <!-- Conteúdo Principal (scrollável) -->
            <div class="flex-1 overflow-y-auto">
                <div class="p-6 md:p-8">

                    <!-- STEP 1: Seleção de Templates -->
                    @if($step === 1)
                        <div class="space-y-6" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
                                <i class="fas fa-info-circle text-blue-600 text-lg mt-1 flex-shrink-0"></i>
                                <p class="text-sm text-blue-900">
                                    Escolha o template que melhor representa seu negócio. Você poderá customizar completamente depois!
                                </p>
                            </div>

                            <!-- Grid de Templates -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @forelse($templates as $template)
                                    <div
                                        wire:click="selectTemplate({{ $template['id'] }})"
                                        class="group cursor-pointer relative bg-white border-2 border-gray-200 rounded-xl overflow-hidden hover:border-blue-500 transition-all duration-300 transform hover:scale-105 hover:shadow-xl"
                                    >
                                        <!-- Imagem/Thumbnail -->
                                        <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center overflow-hidden">
                                            @if($template['thumbnail_url'])
                                                <img
                                                    src="{{ $template['thumbnail_url'] }}"
                                                    alt="{{ $template['name'] }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                                    loading="lazy"
                                                >
                                            @else
                                                <div class="text-center text-gray-400">
                                                    <i class="fas fa-image text-5xl mb-3 opacity-30"></i>
                                                    <p class="text-sm font-medium">Sem preview disponível</p>
                                                </div>
                                            @endif

                                            <!-- Overlay Badge -->
                                            <div class="absolute top-3 right-3 bg-blue-600 text-white px-3 py-1 rounded-full text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity">
                                                Selecionar
                                            </div>
                                        </div>

                                        <!-- Informações -->
                                        <div class="p-4 bg-white">
                                            <h4 class="font-bold text-gray-900 mb-1 line-clamp-1">{{ $template['name'] }}</h4>
                                            <p class="text-xs text-gray-600 mb-3 line-clamp-2 h-9">{{ $template['description'] ?? 'Sem descrição' }}</p>

                                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                                <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs px-3 py-1 rounded-full font-medium">
                                                    <i class="fas fa-tag text-xs"></i>
                                                    {{ $template['category'] ?? 'Geral' }}
                                                </span>
                                                <i class="fas fa-arrow-right text-blue-600 text-sm group-hover:translate-x-1 transition-transform"></i>
                                            </div>
                                        </div>

                                        <!-- Overlay Hover -->
                                        <div class="absolute inset-0 bg-blue-600 bg-opacity-0 group-hover:bg-opacity-5 transition-all duration-300"></div>
                                    </div>
                                @empty
                                    <div class="col-span-full text-center py-16">
                                        <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                                        <p class="text-gray-500 font-medium">Nenhum template disponível no momento</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    <!-- STEP 2: Configuração do Site -->
                    @elseif($step === 2)
                        <div class="space-y-8 max-w-2xl" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">

                            <!-- Template Selecionado (Preview) -->
                            @if($selectedTemplate)
                                <div class="bg-gradient-to-r from-blue-50 to-purple-50 border border-blue-200 p-5 rounded-xl flex items-center gap-5">
                                    @if($selectedTemplate['thumbnail_url'])
                                        <img
                                            src="{{ $selectedTemplate['thumbnail_url'] }}"
                                            alt="{{ $selectedTemplate['name'] }}"
                                            class="w-20 h-20 rounded-lg object-cover flex-shrink-0 border border-blue-300"
                                        >
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900 mb-1 flex items-center gap-2">
                                            <i class="fas fa-check-circle text-green-600"></i>
                                            {{ $selectedTemplate['name'] }}
                                        </h4>
                                        <p class="text-sm text-gray-600 mb-3">{{ $selectedTemplate['description'] ?? 'Sem descrição' }}</p>
                                        <button
                                            wire:click="goBack"
                                            class="text-sm text-blue-600 hover:text-blue-700 font-medium transition flex items-center gap-1"
                                        >
                                            <i class="fas fa-exchange-alt text-xs"></i> Mudar template
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- Formulário -->
                            <div class="space-y-6">
                                <!-- Nome do Site -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">
                                        <i class="fas fa-heading text-blue-600 mr-2"></i>
                                        Nome do Site <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        wire:model.live="siteName"
                                        placeholder="Ex: Consultoria Silva Advogados"
                                        class="w-full px-4 py-3 border {{ $errors->has('siteName') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white"
                                    >
                                    @error('siteName')
                                        <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-2">Este nome aparecerá no seu site e será usado em metatags</p>
                                </div>

                                <!-- URL do Site (Slug) -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-bold text-gray-900">
                                            <i class="fas fa-link text-blue-600 mr-2"></i>
                                            URL do Site <span class="text-red-500">*</span>
                                        </label>
                                        <button
                                            wire:click="toggleAutoSlug"
                                            type="button"
                                            class="text-xs font-bold transition flex items-center gap-1 {{ $autoGenerateSlug ? 'text-green-600 hover:text-green-700' : 'text-orange-600 hover:text-orange-700' }}"
                                        >
                                            @if($autoGenerateSlug)
                                                <i class="fas fa-lock"></i> Auto
                                            @else
                                                <i class="fas fa-pen"></i> Manual
                                            @endif
                                        </button>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 relative">
                                            <input
                                                type="text"
                                                wire:model.live="siteSlug"
                                                :disabled="$autoGenerateSlug"
                                                placeholder="seu-site"
                                                class="w-full px-4 py-3 border {{ $errors->has('siteSlug') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed"
                                            >
                                        </div>
                                        <span class="text-gray-600 font-bold whitespace-nowrap text-sm">.sitesdafabrica.com.br</span>
                                    </div>

                                    @error('siteSlug')
                                        <p class="text-red-600 text-sm mt-2 flex items-center gap-1">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </p>
                                    @enderror

                                    <div class="text-xs text-gray-500 mt-2 space-y-1">
                                        <p>✓ Apenas letras minúsculas, números e hífens</p>
                                        <p>✓ Deve ser único (não pode repetir com outro site)</p>
                                        <p>✓ Você pode trocar para domínio personalizado depois</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Informações do Plano -->
                            <div class="border-t pt-6">
                                @if($subscription)
                                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-5 rounded-lg border border-green-200">
                                        <div class="flex items-start gap-4">
                                            <i class="fas fa-star text-yellow-500 text-xl mt-1 flex-shrink-0"></i>
                                            <div>
                                                <p class="text-sm font-bold text-gray-900">
                                                    Plano: <span class="text-green-700">{{ $subscription?->plan->name }}</span>
                                                </p>
                                                <p class="text-xs text-gray-600 mt-2">
                                                    Você pode criar até <strong class="text-green-700">{{ $subscription?->plan->max_sites }} site(s)</strong> neste plano
                                                </p>
                                                <p class="text-xs text-gray-600 mt-1">
                                                    Uso atual: <strong>{{ $siteCountMessage }}</strong>
                                                </p>
                                                <p class="text-xs text-gray-600 mt-2">
                                                    Armazenamento: <strong>{{ $subscription?->plan->max_storage_gb ?? 0 }} GB</strong> •
                                                    Banda: <strong>{{ $subscription?->plan->max_bandwidth_gb ?? 0 }} GB</strong>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-5 rounded-lg border border-amber-200 flex items-start gap-4">
                                        <i class="fas fa-info-circle text-amber-600 text-xl mt-1 flex-shrink-0"></i>
                                        <div>
                                            <p class="text-sm font-bold text-amber-900 mb-2">Plano Gratuito</p>
                                            <p class="text-xs text-amber-800 mb-3">Você está usando o plano grátis (1 site, 500MB storage)</p>
                                            <a href="{{ route('pricing') }}" class="text-xs font-bold text-amber-700 hover:text-amber-900 transition inline-flex items-center gap-1">
                                                <i class="fas fa-arrow-up-right"></i> Ver planos premium
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                    <!-- STEP 3: Revisão e Confirmação -->
                    @else
                        <div class="max-w-2xl space-y-6" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">

                            <p class="text-gray-700 text-base flex items-center gap-2">
                                <i class="fas fa-clipboard-check text-blue-600"></i>
                                Confirme as informações do seu novo site antes de criar:
                            </p>

                            <!-- Card de Revisão -->
                            <div class="border-2 border-gray-200 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                                <!-- Thumbnail do Template -->
                                @if($selectedTemplate['thumbnail_url'])
                                    <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden relative group">
                                        <img
                                            src="{{ $selectedTemplate['thumbnail_url'] }}"
                                            alt="{{ $selectedTemplate['name'] }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        >
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all"></div>
                                    </div>
                                @endif

                                <!-- Detalhes da Revisão -->
                                <div class="p-8 bg-white space-y-6">

                                    <!-- Grid 2 Colunas -->
                                    <div class="grid grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">
                                                <i class="fas fa-image mr-2 text-blue-600"></i> Template
                                            </p>
                                            <p class="text-lg font-bold text-gray-900">{{ $selectedTemplate['name'] }}</p>
                                            <p class="text-xs text-gray-600">{{ $selectedTemplate['category'] ?? 'Geral' }}</p>
                                        </div>

                                        <div class="space-y-2">
                                            <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">
                                                <i class="fas fa-heading mr-2 text-blue-600"></i> Nome do Site
                                            </p>
                                            <p class="text-lg font-bold text-gray-900">{{ $siteName }}</p>
                                        </div>
                                    </div>

                                    <!-- URL -->
                                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg space-y-2">
                                        <p class="text-xs text-gray-600 uppercase font-bold tracking-wider">
                                            <i class="fas fa-globe mr-2 text-blue-600"></i> URL Pública
                                        </p>
                                        <p class="text-xl font-bold text-blue-700 break-all">
                                            https://{{ $siteSlug }}.sitesdafabrica.com.br
                                        </p>
                                    </div>

                                    <!-- Descrição -->
                                    <div>
                                        <p class="text-xs text-gray-600 uppercase font-bold tracking-wider mb-2">
                                            <i class="fas fa-align-left mr-2 text-blue-600"></i> Descrição
                                        </p>
                                        <p class="text-gray-700 leading-relaxed">{{ $selectedTemplate['description'] ?? 'Sem descrição' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Checklist de Informações -->
                            <div class="space-y-3 bg-gray-50 p-6 rounded-lg border border-gray-200">
                                <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <i class="fas fa-list-check text-blue-600"></i> O que você pode fazer depois:
                                </h4>

                                <div class="space-y-3">
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-check-circle text-green-600 text-lg flex-shrink-0 mt-0.5"></i>
                                        <span class="text-sm text-gray-700">Customizar cores, textos e imagens</span>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-check-circle text-green-600 text-lg flex-shrink-0 mt-0.5"></i>
                                        <span class="text-sm text-gray-700">Adicionar novas páginas e seções</span>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-check-circle text-green-600 text-lg flex-shrink-0 mt-0.5"></i>
                                        <span class="text-sm text-gray-700">Mudar para domínio personalizado</span>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-check-circle text-green-600 text-lg flex-shrink-0 mt-0.5"></i>
                                        <span class="text-sm text-gray-700">Ativar analytics e monitorar visitantes</span>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-check-circle text-green-600 text-lg flex-shrink-0 mt-0.5"></i>
                                        <span class="text-sm text-gray-700">Seu site começará como <strong>rascunho</strong> (privado)</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Warning -->
                            <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded flex items-start gap-3">
                                <i class="fas fa-lightbulb text-blue-600 text-lg flex-shrink-0 mt-0.5"></i>
                                <p class="text-sm text-blue-900">
                                    <strong>Dica:</strong> Você pode consultar nossa documentação durante o processo de edição se tiver dúvidas.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Footer Actions (Sticky) -->
            <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 md:px-8 py-4 flex items-center justify-between gap-4 bg-gradient-to-r from-gray-50 to-white">
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
                            class="px-6 py-2 border border-gray-300 text-gray-700 font-medium hover:bg-gray-100 rounded-lg transition flex items-center gap-2"
                        >
                            <i class="fas fa-arrow-left"></i> Voltar
                        </button>
                    @endif

                    @if($step < 3)
                        <button
                            wire:click="goToReview"
                            class="px-8 py-2 bg-blue-600 text-white font-bold hover:bg-blue-700 active:scale-95 rounded-lg transition transform flex items-center gap-2"
                        >
                            Próximo
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    @else
                        <button
                            wire:click="createSite"
                            wire:loading.attr="disabled"
                            :disabled="$isLoading"
                            class="px-8 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold hover:from-green-700 hover:to-emerald-700 active:scale-95 rounded-lg transition transform disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                        >
                            <span wire:loading.remove>
                                <i class="fas fa-check"></i> Criar Site Agora
                            </span>
                            <span wire:loading class="flex items-center gap-2">
                                <i class="fas fa-spinner animate-spin"></i> Criando...
                            </span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications (opcional) -->
    <script>
        document.addEventListener('livewire:navigated', () => {
            @this.closeModal();
        });
    </script>
</div>