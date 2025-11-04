<!-- resources/views/components/create-site-modal.blade.php -->
<div x-data="siteModalApp()" class="relative">
    <!-- Botão para abrir modal -->
    <button
        @click="isOpen = true"
        class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition"
    >
        <i class="fas fa-rocket mr-2"></i> Criar Site Agora
    </button>

    <!-- Modal Backdrop -->
    <div
        x-show="isOpen"
        @click="isOpen = false"
        class="fixed inset-0 bg-black/50 z-40 transition-opacity"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    <!-- Modal Principal -->
    <div
        x-show="isOpen"
        class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">

            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">
                        <template x-if="step === 1"><i class="fas fa-image mr-2"></i>Selecione um Template</template>
                        <template x-if="step === 2"><i class="fas fa-cog mr-2"></i>Configure seu Site</template>
                        <template x-if="step === 3"><i class="fas fa-check-circle mr-2"></i>Revisar</template>
                    </h2>
                    <p class="text-blue-100 text-sm mt-1">Passo <span x-text="step"></span> de 3</p>
                </div>
                <button @click="closeModal()" class="text-blue-100 hover:text-white">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <!-- Progress Bar -->
            <div class="h-1 bg-gray-200">
                <div class="h-full bg-blue-600 transition-all duration-300" :style="`width: ${(step / 3) * 100}%`"></div>
            </div>

            <!-- Success Message -->
            <template x-if="success">
                <div class="m-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span x-text="success"></span>
                </div>
            </template>

            <!-- Erro -->
            <template x-if="error">
                <div class="m-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span x-text="error"></span>
                </div>
            </template>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6">

                <!-- STEP 1: Selecionar Template -->
                <template x-if="step === 1">
                    <div>
                        <x-grid-templates />
                        <p class="text-gray-600 mb-4">Escolha o template para seu site:</p>
                        <template x-if="templates.length === 0">
                            <div class="text-center py-12">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-600 mb-2">Nenhum template disponível</p>
                                <p class="text-sm text-gray-500">Os templates estão sendo carregados...</p>
                            </div>
                        </template>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" x-show="templates.length > 0">
                            <template x-for="template in templates" :key="template.id">
                                <div
                                    @click="selectedTemplateId = template.id; step = 2"
                                    class="cursor-pointer group p-4 bg-white border-2 border-gray-200 rounded-lg hover:border-blue-500 hover:shadow-lg transition-all"
                                >
                                    <div class="h-24 bg-gray-100 rounded mb-3 flex items-center justify-center overflow-hidden">
                                        <template x-if="template.thumbnail_url">
                                            <img :src="template.thumbnail_url" :alt="template.name" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!template.thumbnail_url">
                                            <i class="fas fa-image text-gray-400 text-3xl"></i>
                                        </template>
                                    </div>
                                    <h4 class="font-bold text-gray-900 mb-1" x-text="template.name"></h4>
                                    <p class="text-xs text-gray-600" x-text="template.description"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <!-- STEP 2: Configurar Site -->
                <template x-if="step === 2">
                    <div class="space-y-4 max-w-lg">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">Nome do Site *</label>
                            <input
                                type="text"
                                x-model="siteName"
                                @input="autoGenerateSlug()"
                                placeholder="Ex: Consultoria Silva"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 mb-2">URL do Site *</label>
                            <div class="flex items-center gap-2">
                                <input
                                    type="text"
                                    x-model="siteSlug"
                                    placeholder="seu-site"
                                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                                <span class="text-gray-600 font-bold">.com.br</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Apenas minúsculas, números e hífens</p>
                        </div>
                    </div>
                </template>

                <!-- STEP 3: Revisar -->
                <template x-if="step === 3">
                    <div class="space-y-4 max-w-lg">
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                            <div class="space-y-2 text-sm">
                                <div>
                                    <p class="text-xs text-gray-600 font-bold">Template</p>
                                    <p class="font-bold text-gray-900" x-text="getTemplateName()"></p>
                                </div>
                                <div class="pt-2 border-t">
                                    <p class="text-xs text-gray-600 font-bold">Nome</p>
                                    <p class="font-bold text-gray-900" x-text="siteName"></p>
                                </div>
                                <div class="pt-2 border-t">
                                    <p class="text-xs text-gray-600 font-bold">URL</p>
                                    <p class="font-bold text-blue-600" x-text="'https://' + siteSlug + '.com.br'"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Footer -->
            <div class="border-t bg-gray-50 px-6 py-4 flex items-center justify-between gap-4">
                <button
                    @click="closeModal()"
                    class="px-6 py-2 text-gray-700 font-medium hover:bg-gray-100 rounded-lg"
                >
                    Cancelar
                </button>

                <div class="flex gap-3">
                    <button
                        x-show="step > 1"
                        @click="step--; error = ''; success = '';"
                        class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100"
                    >
                        Voltar
                    </button>

                    <button
                        x-show="step < 3"
                        @click="nextStep()"
                        class="px-8 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700"
                    >
                        Próximo
                    </button>

                    <button
                        x-show="step === 3"
                        @click="createSite()"
                        :disabled="loading"
                        class="px-8 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 disabled:opacity-50"
                    >
                        <span x-show="!loading">Criar Site</span>
                        <span x-show="loading"><i class="fas fa-spinner animate-spin mr-2"></i>Criando...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Alpine.js -->
    <script>
        function siteModalApp() {
            return {
                isOpen: false,
                step: 1,
                loading: false,
                error: '',
                success: '',
                templates: @json($templates ?? []),
                selectedTemplateId: null,
                siteName: '',
                siteSlug: '',

                getTemplateName() {
                    const t = this.templates.find(x => x.id === this.selectedTemplateId);
                    return t ? t.name : '';
                },

                autoGenerateSlug() {
                    this.siteSlug = this.siteName
                        .toLowerCase()
                        .trim()
                        .normalize('NFD')
                        .replace(/[\u0300-\u036f]/g, '')
                        .replace(/[^\w\s-]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-')
                        .replace(/^-+|-+$/g, '');
                },

                nextStep() {
                    this.error = '';
                    this.success = '';
                    
                    if (this.step === 1 && !this.selectedTemplateId) {
                        this.error = 'Selecione um template';
                        return;
                    }
                    
                    if (this.step === 2) {
                        if (!this.siteName || this.siteName.length < 3) {
                            this.error = 'Nome deve ter pelo menos 3 caracteres';
                            return;
                        }
                        if (!this.siteSlug || this.siteSlug.length < 3) {
                            this.error = 'URL inválida';
                            return;
                        }
                    }
                    
                    this.step++;
                },

                closeModal() {
                    this.isOpen = false;
                    this.step = 1;
                    this.error = '';
                    this.success = '';
                    this.siteName = '';
                    this.siteSlug = '';
                    this.selectedTemplateId = null;
                },

                async createSite() {
                    this.loading = true;
                    this.error = '';
                    this.success = '';

                    try {
                        // Enviar via AJAX POST
                        const response = await fetch('{{ route("sites.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                name: this.siteName,
                                slug: this.siteSlug,
                                template_id: this.selectedTemplateId,
                            })
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Erro ao criar site');
                        }

                        // Sucesso! Mostrar mensagem e fechar
                        this.success = '✅ Site criado com sucesso!';
                        
                        setTimeout(() => {
                            this.closeModal();
                            // Recarregar a lista de sites na página
                            location.reload();
                        }, 2000);

                    } catch (error) {
                        this.error = error.message || 'Erro ao criar site';
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</div>