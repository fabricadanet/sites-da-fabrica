<!-- resources/views/components/landing-footer.blade.php -->

@props([
    'companyName' => 'Sites da Fábrica',
    'companyDescription' => 'Criando websites profissionais de forma simples e acessível.',
    'year' => now()->year,
    'socialMedia' => [
        ['icon' => 'fab fa-facebook', 'url' => '#', 'label' => 'Facebook'],
        ['icon' => 'fab fa-twitter', 'url' => '#', 'label' => 'Twitter'],
        ['icon' => 'fab fa-linkedin', 'url' => '#', 'label' => 'LinkedIn'],
        ['icon' => 'fab fa-instagram', 'url' => '#', 'label' => 'Instagram']
    ],
    'productLinks' => [
        ['label' => 'Recursos', 'url' => '#features'],
        ['label' => 'Planos', 'url' => '#planos'],
        ['label' => 'Templates', 'url' => '#templates']
    ],
    'companyLinks' => [
        ['label' => 'Sobre Nós', 'url' => '#about'],
        ['label' => 'Blog', 'url' => '#blog'],
        ['label' => 'Contato', 'url' => '#contact']
    ],
    'legalLinks' => [
        ['label' => 'Privacidade', 'url' => '#privacy'],
        ['label' => 'Termos', 'url' => '#terms'],
        ['label' => 'Cookies', 'url' => '#cookies']
    ]
])

<footer class="bg-gradient-to-b from-gray-900 to-black text-gray-400 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Footer Top Content -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            
            <!-- Company Info -->
            <div class="col-span-1">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-globe text-white text-lg"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white">{{ $companyName }}</h3>
                </div>
                <p class="text-sm text-gray-500 leading-relaxed">
                    {{ $companyDescription }}
                </p>
            </div>

            <!-- Product Links -->
            <div>
                <h4 class="font-bold text-white mb-6 text-sm uppercase tracking-wider">
                    Produto
                </h4>
                <ul class="space-y-4">
                    @foreach($productLinks as $link)
                        <li>
                            <a 
                                href="{{ $link['url'] }}" 
                                class="text-gray-500 hover:text-blue-400 transition-colors text-sm"
                            >
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Company Links -->
            <div>
                <h4 class="font-bold text-white mb-6 text-sm uppercase tracking-wider">
                    Empresa
                </h4>
                <ul class="space-y-4">
                    @foreach($companyLinks as $link)
                        <li>
                            <a 
                                href="{{ $link['url'] }}" 
                                class="text-gray-500 hover:text-blue-400 transition-colors text-sm"
                            >
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Legal Links -->
            <div>
                <h4 class="font-bold text-white mb-6 text-sm uppercase tracking-wider">
                    Legal
                </h4>
                <ul class="space-y-4">
                    @foreach($legalLinks as $link)
                        <li>
                            <a 
                                href="{{ $link['url'] }}" 
                                class="text-gray-500 hover:text-blue-400 transition-colors text-sm"
                            >
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-800 my-8"></div>

        <!-- Footer Bottom -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            
            <!-- Copyright -->
            <p class="text-sm text-gray-600 text-center md:text-left">
                &copy; {{ $year }} {{ $companyName }}. Todos os direitos reservados.
            </p>

            <!-- Social Media -->
            <div class="flex gap-6">
                @foreach($socialMedia as $social)
                    <a 
                        href="{{ $social['url'] }}" 
                        title="{{ $social['label'] }}"
                        class="text-gray-600 hover:text-blue-400 transition-colors duration-300 transform hover:scale-125"
                    >
                        <i class="{{ $social['icon'] }} text-xl"></i>
                    </a>
                @endforeach
            </div>

            <!-- Additional Links (Mobile friendly) -->
            <div class="flex gap-4 text-sm text-gray-600">
                <a href="#status" class="hover:text-blue-400 transition-colors">Status</a>
                <span class="text-gray-800">•</span>
                <a href="#sitemap" class="hover:text-blue-400 transition-colors">Sitemap</a>
            </div>
        </div>

        <!-- Extra Info Banner (Optional) -->
        <div class="mt-12 pt-8 border-t border-gray-800 bg-gradient-to-r from-blue-900/20 to-purple-900/20 rounded-lg p-6 text-center">
            <p class="text-sm text-gray-500 mb-4">
                <i class="fas fa-leaf text-green-500 mr-2"></i>
                Hospedagem 100% sustentável com energia renovável
            </p>
            <p class="text-xs text-gray-700">
                Desenvolvido com <i class="fas fa-heart text-red-500"></i> para empreendedores brasileiros.
            </p>
        </div>
    </div>
</footer>

<style>
    footer a {
        position: relative;
        overflow: hidden;
    }

    footer a::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: -100%;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, #3B82F6, #8B5CF6);
        transition: left 0.3s ease;
    }

    footer a:hover::after {
        left: 0;
    }
</style>