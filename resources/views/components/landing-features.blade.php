<!-- resources/views/components/landing-features.blade.php -->

@props([
    'features' => [
        [
            'icon' => 'fas fa-magic',
            'title' => 'Templates Prontos',
            'description' => '11+ templates profissionais para diferentes nichos. Advocacia, Eventos, Médicos e muito mais!'
        ],
        [
            'icon' => 'fas fa-sliders-h',
            'title' => '100% Customizável',
            'description' => 'Altere cores, textos, imagens e layout sem sair da plataforma. Seu jeito, sua marca.'
        ],
        [
            'icon' => 'fas fa-mobile-alt',
            'title' => 'Totalmente Responsivo',
            'description' => 'Seu site fica perfeito em desktop, tablet e celular. Sem exceção.'
        ],
        [
            'icon' => 'fas fa-chart-line',
            'title' => 'Analytics Integrado',
            'description' => 'Acompanhe quem visita seu site e de onde vem. Dados em tempo real.'
        ],
        [
            'icon' => 'fas fa-globe',
            'title' => 'Domínio Personalizado',
            'description' => 'Use seu próprio domínio ou um subdomínio gratuito. Com SSL incluído.'
        ],
        [
            'icon' => 'fab fa-whatsapp',
            'title' => 'WhatsApp Integrado',
            'description' => 'Botão flutuante do WhatsApp para receber mensagens diretamente dos seus clientes.',
            'color' => 'text-green-500'
        ],
        [
            'icon' => 'fas fa-lock',
            'title' => 'Seguro e Confiável',
            'description' => 'Hospedagem em servidores de ponta com backup automático diário.'
        ],
        [
            'icon' => 'fas fa-headset',
            'title' => 'Suporte em Português',
            'description' => 'Dúvidas? Estamos aqui para ajudar via email, WhatsApp ou chat ao vivo.'
        ]
    ]
])

<section id="features" class="py-20 px-4 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black mb-6 text-gray-900 leading-tight">
                Por que escolher <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Sites da Fábrica?</span>
            </h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Tudo que você precisa para ter presença online profissional
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($features as $feature)
                <div class="group card-hover bg-white p-8 rounded-xl border border-gray-200 hover:border-blue-500 transition-all duration-300">
                    <!-- Icon -->
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <i class="{{ $feature['icon'] }} text-white text-2xl {{ $feature['color'] ?? '' }}"></i>
                    </div>

                    <!-- Title -->
                    <h3 class="text-xl font-bold mb-3 text-gray-900 group-hover:text-blue-600 transition-colors">
                        {{ $feature['title'] }}
                    </h3>

                    <!-- Description -->
                    <p class="text-gray-600 text-sm leading-relaxed">
                        {{ $feature['description'] }}
                    </p>
                </div>
            @endforeach
        </div>

        <!-- Optional: Call to Action Banner -->
        <div class="mt-20 bg-gradient-to-r from-blue-50 to-purple-50 p-10 rounded-2xl border border-blue-100">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        Pronto para começar?
                    </h3>
                    <p class="text-gray-600">
                        Junte-se a milhares de empreendedores que já têm seus sites profissionais rodando.
                    </p>
                </div>
                <button onclick="scrollToSection('cta')" class="bg-gradient-to-r from-blue-600 to-blue-800 text-white px-8 py-3 rounded-lg font-bold hover:shadow-lg transform hover:scale-105 transition-all duration-300 whitespace-nowrap">
                    <i class="fas fa-rocket mr-2"></i> Começar Agora
                </button>
            </div>
        </div>
    </div>
</section>

<style>
    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
</style>