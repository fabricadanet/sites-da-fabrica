<!-- resources/views/dashboard.blade.php -->
<x-app-layout>
    <div class="p-6 md:p-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                👋 Bem-vindo, {{ Auth::user()->name }}!
            </h1>
            <p class="text-gray-600">
                {{ now()->format('l, d \d\e F \d\e Y') }}
            </p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            
            <!-- Plano Atual -->
            <div class="card">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-star text-blue-600 text-lg"></i>
                    </div>
                    <span class="badge badge-primary text-xs">
                        {{ Auth::user()->subscription()?->status_label ?? 'N/A' }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 mb-1">Plano Atual</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ Auth::user()->subscription()?->plan?->name ?? 'Nenhum' }}
                </p>
            </div>

            <!-- Dias de Trial -->
            @if(Auth::user()->subscription()?->isOnTrial())
                <div class="card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-orange-600 text-lg"></i>
                        </div>
                        <span class="badge badge-warning text-xs">
                            Teste Grátis
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-1">Dias Restantes</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ Auth::user()->subscription()->daysRemaining() ?? 0 }}
                    </p>
                    <p class="text-xs text-gray-500 mt-2">
                        Expira: {{ Auth::user()->subscription()->trial_ends_at?->format('d/m/Y') }}
                    </p>
                </div>
            @else
                <div class="card">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-lg"></i>
                        </div>
                        <span class="badge badge-success text-xs">
                            Ativo
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-1">Status</p>
                    <p class="text-2xl font-bold text-gray-900">
                        Assinatura Ativa
                    </p>
                </div>
            @endif

            <!-- Sites Criados -->
            <div class="card">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-globe text-purple-600 text-lg"></i>
                    </div>
                    <span class="badge badge-primary text-xs">
                        Ativos
                    </span>
                </div>
                <p class="text-sm text-gray-600 mb-1">Meus Sites</p>
                <p class="text-2xl font-bold text-gray-900">
                    0
                </p>
                <p class="text-xs text-gray-500 mt-2">
                    Máximo: {{ Auth::user()->subscription()?->plan?->max_sites ?? 'Ilimitado' }}
                </p>
            </div>

            <!-- Visitantes -->
            <div class="card">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-eye text-pink-600 text-lg"></i>
                    </div>
                    <span class="badge badge-primary text-xs">
                        Esse Mês
                    </span>
                </div>
                <p class="text-sm text-gray-600 mb-1">Visitantes</p>
                <p class="text-2xl font-bold text-gray-900">
                    0
                </p>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Ações Principais -->
            <div class="lg:col-span-2">
                <div class="card">
                    <h2 class="text-lg font-bold text-gray-900 mb-6">
                        🚀 Próximos Passos
                    </h2>

                    <div class="space-y-4">
                        <!-- Criar Site -->
                        <div class="flex items-start gap-4 p-4 bg-blue-50 rounded-lg border border-blue-100">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-plus text-blue-600 text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1">
                                    Criar Seu Primeiro Site
                                </h3>
                                <p class="text-sm text-gray-600 mb-3">
                                    Escolha um template e comece a construir seu website profissional agora mesmo.
                                </p>
                                <button class="btn-primary text-sm">
                                    <i class="fas fa-rocket mr-2"></i> Criar Site Agora
                                </button>
                            </div>
                        </div>

                        <!-- Upgrade -->
                        @if(Auth::user()->subscription()?->isOnTrial())
                            <div class="flex items-start gap-4 p-4 bg-purple-50 rounded-lg border border-purple-100">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-crown text-purple-600 text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 mb-1">
                                        Upgrade Seu Plano
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-3">
                                        Desbloqueie recursos ilimitados e leve seu negócio ao próximo nível.
                                    </p>
                                    <button class="btn-primary text-sm">
                                        <i class="fas fa-arrow-up mr-2"></i> Ver Planos
                                    </button>
                                </div>
                            </div>
                        @endif

                        <!-- Guia -->
                        <div class="flex items-start gap-4 p-4 bg-green-50 rounded-lg border border-green-100">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-book text-green-600 text-lg"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 mb-1">
                                    Leia Nossa Documentação
                                </h3>
                                <p class="text-sm text-gray-600 mb-3">
                                    Aprenda como maximizar o potencial da plataforma com nossos guias e tutoriais.
                                </p>
                                <button class="btn-secondary text-sm">
                                    <i class="fas fa-external-link-alt mr-2"></i> Abrir Docs
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                
                <!-- Informações da Conta -->
                <div class="card">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">
                        👤 Sua Conta
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Nome</p>
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Email</p>
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->email }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Membro Desde</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ Auth::user()->created_at->format('d/m/Y') }}
                            </p>
                        </div>

                        <hr class="my-4">

                        <a href="#" class="block text-sm text-blue-600 hover:text-blue-700 font-semibold">
                            <i class="fas fa-edit mr-2"></i> Editar Perfil
                        </a>
                        <a href="#" class="block text-sm text-blue-600 hover:text-blue-700 font-semibold">
                            <i class="fas fa-lock mr-2"></i> Alterar Senha
                        </a>
                    </div>
                </div>

                <!-- Support -->
                <div class="card bg-gradient-to-br from-blue-50 to-blue-100 border-blue-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                        💬 Precisa de Ajuda?
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Nossa equipe está aqui para ajudá-lo com qualquer dúvida.
                    </p>
                    <div class="space-y-2">
                       
                        <button class="btn-secondary w-full text-sm">
                            <i class="fas fa-envelope mr-2"></i> Email
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
