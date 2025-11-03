<x-app-layout>
    <div class="flex h-screen bg-gray-900">
        
        <!-- Left Sidebar - Pages -->
        <div class="w-64 bg-gray-800 border-r border-gray-700 overflow-y-auto">
            <div class="p-4 border-b border-gray-700">
                <h2 class="text-white font-bold mb-4">Páginas</h2>
                <button 
                    onclick="addPage()"
                    class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center justify-center gap-2"
                >
                    <i class="fas fa-plus"></i> Nova Página
                </button>
            </div>

            <div class="p-4 space-y-2">
                @foreach($site->pages as $page)
                    <div class="group relative">
                        <button 
                            onclick="selectPage({{ $page->id }})"
                            class="w-full text-left px-4 py-2 rounded-lg text-gray-300 hover:bg-gray-700 transition {{ $loop->first ? 'bg-gray-700' : '' }}"
                        >
                            <div class="flex items-center gap-2">
                                <i class="fas {{ $page->is_home ? 'fa-home' : 'fa-file' }}"></i>
                                <span class="flex-1 truncate">{{ $page->title }}</span>
                                @if($page->is_published)
                                    <i class="fas fa-check-circle text-green-500 text-xs"></i>
                                @endif
                            </div>
                        </button>
                        <div class="absolute right-2 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition">
                            <button class="p-1 text-gray-400 hover:text-gray-300">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Main Editor Area -->
        <div class="flex-1 flex flex-col">
            
            <!-- Top Header -->
            <div class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('tenant.sites.index') }}" class="text-gray-400 hover:text-white transition">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-white font-bold">{{ $site->name }}</h1>
                        <p class="text-gray-400 text-sm">{{ $site->template->name }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Preview Button -->
                    <a 
                        href="{{ route('tenant.sites.preview', $site->slug) }}"
                        target="_blank"
                        class="px-4 py-2 text-gray-300 hover:text-white transition flex items-center gap-2"
                    >
                        <i class="fas fa-eye"></i> Preview
                    </a>

                    <!-- Publish Button -->
                    @if($site->is_published)
                        <button 
                            onclick="unpublishSite()"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition flex items-center gap-2"
                        >
                            <i class="fas fa-times-circle"></i> Despublicar
                        </button>
                    @else
                        <button 
                            onclick="publishSite()"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition flex items-center gap-2"
                        >
                            <i class="fas fa-rocket"></i> Publicar
                        </button>
                    @endif

                    <!-- Settings -->
                    <button class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition">
                        <i class="fas fa-cog"></i>
                    </button>
                </div>
            </div>

            <!-- Editor Canvas Area -->
            <div class="flex-1 flex gap-4 p-6 overflow-hidden bg-gray-900">
                
                <!-- Canvas (Center) -->
                <div class="flex-1 flex flex-col">
                    <div class="flex-1 bg-white rounded-lg shadow-lg overflow-y-auto border-4 border-gray-700">
                        <iframe 
                            id="sitePreview"
                            src="{{ route('tenant.sites.preview', $site->slug) }}"
                            class="w-full h-full border-none"
                        ></iframe>
                    </div>
                </div>

                <!-- Right Sidebar - Inspector/Properties -->
                <div class="w-80 bg-gray-800 rounded-lg border border-gray-700 overflow-y-auto">
                    <div class="p-4 border-b border-gray-700">
                        <h3 class="text-white font-bold mb-4">Propriedades</h3>
                    </div>

                    <div class="p-4 space-y-4">
                        <!-- Site Settings -->
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Título do Site</label>
                            <input 
                                type="text"
                                value="{{ $site->name }}"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <!-- Colors -->
                        <div>
                            <label class="block text-gray-300 text-sm font-medium mb-2">Cor Primária</label>
                            <div class="flex gap-2">
                                <input 
                                    type="color"
                                    value="{{ $site->config['primary_color'] ?? '#3B82F6' }}"
                                    class="w-12 h-10 rounded-lg cursor-pointer"
                                >
                                <input 
                                    type="text"
                                    value="{{ $site->config['primary_color'] ?? '#3B82F6' }}"
                                    class="flex-1 px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                                >
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-700"></div>

                        <!-- Components Available -->
                        <div>
                            <h4 class="text-gray-300 text-sm font-bold mb-3">Componentes</h4>
                            <div class="space-y-2">
                                <button class="w-full px-3 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white rounded-lg transition text-left text-sm flex items-center gap-2 transition">
                                    <i class="fas fa-heading"></i> Seção Hero
                                </button>
                                <button class="w-full px-3 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white rounded-lg transition text-left text-sm flex items-center gap-2 transition">
                                    <i class="fas fa-th"></i> Características
                                </button>
                                <button class="w-full px-3 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white rounded-lg transition text-left text-sm flex items-center gap-2 transition">
                                    <i class="fas fa-images"></i> Galeria
                                </button>
                                <button class="w-full px-3 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white rounded-lg transition text-left text-sm flex items-center gap-2 transition">
                                    <i class="fas fa-comment"></i> Depoimentos
                                </button>
                                <button class="w-full px-3 py-2 bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white rounded-lg transition text-left text-sm flex items-center gap-2 transition">
                                    <i class="fas fa-tag"></i> CTA
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function publishSite() {
                if (confirm('Tem certeza que deseja publicar este site? Ele ficará visível para todos.')) {
                    fetch('{{ route("tenant.sites.publish", $site->slug) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        alert(data.message);
                        location.reload();
                    })
                    .catch(e => alert('Erro: ' + e.message));
                }
            }

            function unpublishSite() {
                if (confirm('Tem certeza que deseja despublicar este site?')) {
                    fetch('{{ route("tenant.sites.unpublish", $site->slug) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        alert(data.message);
                        location.reload();
                    })
                    .catch(e => alert('Erro: ' + e.message));
                }
            }

            function selectPage(pageId) {
                // Implementar seleção de página
                console.log('Página selecionada:', pageId);
            }

            function addPage() {
                // Implementar adição de página
                const name = prompt('Nome da página:');
                if (name) {
                    // Fazer requisição AJAX
                    console.log('Adicionando página:', name);
                }
            }
        </script>
    @endpush
</x-app-layout>