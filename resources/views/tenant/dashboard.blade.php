<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-black text-gray-900 mb-2">
                    Bem-vindo de volta, {{ auth()->user()->name }}! 👋
                </h1>
                <p class="text-gray-600">
                    Gerencie seus sites e customize como quiser
                </p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Sites -->
                <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium mb-1">Total de Sites</p>
                            <p class="text-3xl font-bold text-gray-900">
                                {{ auth()->user()->sites()->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-globe text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Published Sites -->
                <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium mb-1">Publicados</p>
                            <p class="text-3xl font-bold text-gray-900">
                                {{ auth()->user()->sites()->where('is_published', true)->count() }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Views -->
                <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium mb-1">Visualizações</p>
                            <p class="text-3xl font-bold text-gray-900">
                                {{ auth()->user()->sites()->sum('total_views') }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-eye text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Plan Info -->
                @php
                    $subscription = auth()->user()->subscriptions()->active()->first();
                    $plan = $subscription?->plan;
                @endphp
                <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium mb-1">Plano Atual</p>
                            <p class="text-lg font-bold text-gray-900">
                                {{ $plan?->name ?? 'Gratuito' }}
                            </p>
                            @if($plan)
                                <p class="text-xs text-green-600 mt-1">
                                    {{ $plan->max_sites - auth()->user()->sites()->count() }} site(s) disponível(is)
                                </p>
                            @endif
                        </div>
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-crown text-orange-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="space-y-4 mb-8">
                @if(auth()->user()->sites()->count() === 0)
                    <div class="flex items-start gap-4 p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl border-2 border-blue-200">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-rocket text-white text-2xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900 text-lg mb-1">
                                Vamos começar! 🎉
                            </h3>
                            <p class="text-gray-700 mb-4">
                                Crie seu primeiro site profissional em minutos com nossos templates prontos. 
                            </p>
                            <livewire:create-site-modal />
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-between bg-white rounded-xl p-6 border border-gray-200">
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">Criar novo site</h3>
                            <p class="text-sm text-gray-600">
                                Você tem mais {{ ($plan?->max_sites ?? 1) - auth()->user()->sites()->count() }} site(s) disponível(is) no seu plano
                            </p>
                        </div>
                        <livewire:create-site-modal />
                    </div>
                @endif
            </div>

            <!-- Sites List -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Seus Sites</h2>
                </div>

                @if(auth()->user()->sites()->exists())
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Nome</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">URL</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Template</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Visualizações</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse(auth()->user()->sites()->latest()->get() as $site)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-globe text-white"></i>
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900">{{ $site->name }}</p>
                                                    <p class="text-xs text-gray-500">Criado {{ $site->created_at->format('d/m/Y') }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <a 
                                                href="{{ $site->getUrl() }}" 
                                                target="_blank"
                                                class="text-blue-600 hover:text-blue-700 font-medium text-sm break-all"
                                            >
                                                {{ $site->use_custom_domain ? $site->custom_domain : $site->subdomain . '.sitesdafabrica.com.br' }}
                                                <i class="fas fa-external-link-alt ml-1"></i>
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-block bg-gray-100 text-gray-800 text-xs px-3 py-1 rounded-full font-medium">
                                                {{ $site->template->name }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($site->is_published)
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
                                                    <span class="text-sm font-medium text-green-700">Publicado</span>
                                                </div>
                                            @else
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full"></span>
                                                    <span class="text-sm font-medium text-yellow-700">Rascunho</span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="font-semibold text-gray-900">{{ $site->total_views }}</p>
                                            <p class="text-xs text-gray-500">{{ $site->unique_visitors }} únicos</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <a 
                                                    href="{{ route('tenant.sites.edit', $site->slug) }}"
                                                    class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition"
                                                    title="Editar"
                                                >
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a 
                                                    href="{{ $site->getUrl() }}"
                                                    target="_blank"
                                                    class="p-2 bg-purple-50 hover:bg-purple-100 text-purple-600 rounded-lg transition"
                                                    title="Visualizar"
                                                >
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button 
                                                    class="p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition"
                                                    title="Mais ações"
                                                >
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="space-y-3">
                                                <i class="fas fa-inbox text-4xl text-gray-300"></i>
                                                <p class="text-gray-600">Nenhum site criado ainda</p>
                                                <livewire:create-site-modal />
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-600 mb-6">Você ainda não criou nenhum site</p>
                        <livewire:create-site-modal />
                    </div>
                @endif
            </div>

            <!-- Help Section -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                    <i class="fas fa-book text-3xl text-blue-600 mb-3"></i>
                    <h3 class="font-bold text-gray-900 mb-2">Documentação</h3>
                    <p class="text-sm text-gray-700 mb-4">Aprenda como usar todas as funcionalidades da plataforma</p>
                    <a href="#" class="text-blue-600 hover:text-blue-700 font-medium text-sm">Ler documentação →</a>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                    <i class="fas fa-play-circle text-3xl text-purple-600 mb-3"></i>
                    <h3 class="font-bold text-gray-900 mb-2">Tutoriais em Vídeo</h3>
                    <p class="text-sm text-gray-700 mb-4">Veja passo a passo como criar e customizar seu site</p>
                    <a href="#" class="text-purple-600 hover:text-purple-700 font-medium text-sm">Assistir tutoriais →</a>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                    <i class="fas fa-headset text-3xl text-green-600 mb-3"></i>
                    <h3 class="font-bold text-gray-900 mb-2">Suporte</h3>
                    <p class="text-sm text-gray-700 mb-4">Tem alguma dúvida? Entre em contato com nosso time</p>
                    <a href="#" class="text-green-600 hover:text-green-700 font-medium text-sm">Abrir suporte →</a>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            // Ouvir evento de site criado
            Livewire.on('site-created', (data) => {
                // Opcional: mostrar notificação de sucesso
                console.log('Site criado:', data);
            });
        </script>
    @endpush
</x-app-layout>
