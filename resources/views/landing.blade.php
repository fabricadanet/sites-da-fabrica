<!-- resources/views/landing.blade.php -->
<!-- Landing Page - Versão desenvolvimento (sem Vite) -->

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sites da Fábrica') }} - Crie Seu Website</title>
    
    <!-- Meta tags -->
    <meta name="description" content="Crie seu website profissional em minutos sem conhecimento técnico">
    <meta property="og:title" content="{{ config('app.name') }}">
    <meta property="og:description" content="Plataforma para criar websites profissionais">
    <meta property="og:type" content="website">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Customizado (versão inline) -->
    <style>
        :root {
            --color-primary: #3B82F6;
            --color-primary-dark: #1E40AF;
            --color-secondary: #8B5CF6;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-up {
            animation: fadeInUp 0.8s ease-out;
        }

        .animate-slide-left {
            animation: slideInLeft 0.8s ease-out;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .gradient-text {
            background: linear-gradient(135deg, #3B82F6, #8B5CF6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%);
        }

        .pricing-card {
            border: 2px solid #E5E7EB;
            transition: all 0.3s ease;
        }

        .pricing-card.featured {
            border-color: #3B82F6;
            transform: scale(1.05);
        }

        .pricing-card:hover {
            border-color: #3B82F6;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #3B82F6, #8B5CF6);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3B82F6, #1E40AF);
            color: white;
            padding: 12px 32px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
        }

        .btn-secondary {
            border: 2px solid #3B82F6;
            color: #3B82F6;
            background: white;
            padding: 10px 30px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #3B82F6;
            color: white;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            background: white;
            z-index: 50;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-white">
    
    <!-- Header/Navigation -->
    <header class="sticky-header">
        <nav class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                    <i class="fas fa-globe text-white text-lg"></i>
                </div>
                <span class="text-xl font-bold text-gray-900">{{ config('app.name') }}</span>
            </div>
            <div class="hidden md:flex gap-8 items-center">
                <a href="#features" class="text-gray-600 hover:text-blue-500 transition">Recursos</a>
                <a href="#planos" class="text-gray-600 hover:text-blue-500 transition">Planos</a>
                <a href="#como-funciona" class="text-gray-600 hover:text-blue-500 transition">Como Funciona</a>
                <a href={{route('register')}} class="btn-primary" onclick="scrollToSection('cta')">Começar Agora</a>
            </div>
           
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-gradient text-white py-20 px-4 md:py-32">
        <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
            <div class="animate-fade-up">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black mb-6 leading-tight">
                    Crie seu website <span class="text-blue-200">profissional</span> em minutos
                </h1>
                <p class="text-blue-100 text-lg mb-8 leading-relaxed">
                    Sem conhecimento técnico. Sem código. Sem complicação. Escolha um template, customize e pronto!
                </p>
                <div class="flex gap-4 flex-wrap">
                    <button class="bg-white text-blue-600 px-8 py-3 rounded-lg font-bold hover:bg-blue-50 transition" onclick="scrollToSection('cta')">
                        <i class="fas fa-rocket mr-2"></i> Começar Grátis
                    </button>
                    <button class="border-2 border-white text-white px-8 py-3 rounded-lg font-bold hover:bg-white hover:text-blue-600 transition">
                        <i class="fas fa-play-circle mr-2"></i> Ver Demo
                    </button>
                </div>
                <p class="text-blue-200 text-sm mt-6">✓ Sem cartão de crédito • ✓ 14 dias grátis • ✓ Cancele quando quiser</p>
            </div>
            <div class="animate-slide-left hidden md:block">
                <div class="relative">
                    <div class="absolute inset-0 bg-blue-400 rounded-2xl blur-3xl opacity-20"></div>
                    <div class="relative bg-white rounded-2xl p-8 shadow-2xl">
                        <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg h-64 flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-laptop text-6xl text-gray-400 mb-4"></i>
                                <p class="text-gray-500 font-semibold">Preview do seu site</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <x-landing-features />

    <!-- Como Funciona Section -->
    <section id="como-funciona" class="py-20 px-4 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black mb-6 text-gray-900">Como Funciona (Em 4 Passos!)</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">É simples. Muito simples.</p>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-500 text-white rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">1</div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Escolha um Template</h3>
                    <p class="text-gray-600 text-sm">Navegue por nossos 11+ templates profissionais e escolha o que melhor se encaixa no seu negócio.</p>
                </div>
                <div class="hidden md:flex items-center justify-center">
                    <i class="fas fa-arrow-right text-3xl text-gray-300"></i>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-500 text-white rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">2</div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Customize Tudo</h3>
                    <p class="text-gray-600 text-sm">Altere cores, textos, imagens e adicione suas informações. Nenhum conhecimento técnico necessário.</p>
                </div>
                <div class="hidden md:flex items-center justify-center">
                    <i class="fas fa-arrow-right text-3xl text-gray-300"></i>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-500 text-white rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">3</div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Revise e Aprove</h3>
                    <p class="text-gray-600 text-sm">Visualize como seu site ficará em todos os dispositivos antes de publicar. Tudo ao vivo!</p>
                </div>
                <div class="hidden md:flex items-center justify-center">
                    <i class="fas fa-arrow-right text-3xl text-gray-300"></i>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-500 text-white rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">✓</div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900">Publicar</h3>
                    <p class="text-gray-600 text-sm">Um clique e seu site está no ar! Seu domínio ou um subdomínio nosso. Simples assim!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="planos" class="py-20 px-4 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black mb-6 text-gray-900">Planos Simples e Transparentes</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Escolha o plano ideal para seu negócio. Sem contratos, sem surpresas.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Plano 1 -->
                <div class="pricing-card bg-white rounded-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Gratuito</h3>
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-4xl font-bold text-gray-900">R$ 0</span>
                        <span class="text-gray-600">/mês</span>
                    </div>
                    <button class="btn-secondary w-full mb-8">Começar Agora</button>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-500 mr-2"></i> 1 site ativo</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i> Subdomínio grátis</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i> 5 templates</li>
                        <li><i class="fas fa-times text-red-500 mr-2"></i> Domínio personalizado</li>
                    </ul>
                </div>

                <!-- Plano 2 -->
                <div class="pricing-card bg-white rounded-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Básico</h3>
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-4xl font-bold text-gray-900">R$ 29</span>
                        <span class="text-gray-600">/mês</span>
                    </div>
                    <button class="btn-secondary w-full mb-8">Assinar Agora</button>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-500 mr-2"></i> 3 sites ativos</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i> Sem marca d'água</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i> Todos os templates</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i> 1 domínio personalizado</li>
                    </ul>
                </div>

                <!-- Plano 3 - Featured -->
                <div class="pricing-card featured bg-gradient-to-br from-blue-50 to-white rounded-xl p-8 shadow-xl">
                    <div class="inline-block bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-bold mb-3">
                        MAIS POPULAR ⭐
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Profissional</h3>
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-4xl font-bold text-gray-900">R$ 79</span>
                        <span class="text-gray-600">/mês</span>
                    </div>
                    <button class="btn-primary w-full mb-8">Começar Agora</button>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-500 mr-2"></i> 10 sites ativos</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i> Domínios ilimitados</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i> SSL incluído</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i> Analytics completo</li>
                    </ul>
                </div>

                <!-- Plano 4 -->
                <div class="pricing-card bg-white rounded-xl p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Empresarial</h3>
                    <div class="flex items-baseline gap-1 mb-8">
                        <span class="text-4xl font-bold text-gray-900">R$ 199</span>
                        <span class="text-gray-600">/mês</span>
                    </div>
                    <button class="btn-secondary w-full mb-8">Entre em Contato</button>
                    <ul class="space-y-3 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-500 mr-2"></i> Sites ilimitados</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i> White Label</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i> API acesso</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i> Suporte dedicado</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 px-4 bg-white">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-black mb-6 text-gray-900">Dúvidas Frequentes</h2>
                <p class="text-lg text-gray-600">Tudo que você precisa saber</p>
            </div>

            <div class="space-y-4">
                <details class="bg-gray-50 p-6 rounded-lg cursor-pointer group">
                    <summary class="flex items-center justify-between font-bold text-gray-900">
                        <span>Posso usar meu próprio domínio?</span>
                        <i class="fas fa-chevron-down text-blue-500 group-open:rotate-180 transition"></i>
                    </summary>
                    <p class="text-gray-600 mt-4">Sim! Todos os planos acima de "Básico" permitem usar seu próprio domínio. O SSL é instalado automaticamente.</p>
                </details>

                <details class="bg-gray-50 p-6 rounded-lg cursor-pointer group">
                    <summary class="flex items-center justify-between font-bold text-gray-900">
                        <span>Preciso de conhecimento técnico?</span>
                        <i class="fas fa-chevron-down text-blue-500 group-open:rotate-180 transition"></i>
                    </summary>
                    <p class="text-gray-600 mt-4">Não! {{ config('app.name') }} foi feito para qualquer pessoa. Nossa interface é intuitiva e fácil de usar.</p>
                </details>

                <details class="bg-gray-50 p-6 rounded-lg cursor-pointer group">
                    <summary class="flex items-center justify-between font-bold text-gray-900">
                        <span>Posso cancelar minha assinatura?</span>
                        <i class="fas fa-chevron-down text-blue-500 group-open:rotate-180 transition"></i>
                    </summary>
                    <p class="text-gray-600 mt-4">Claro! Você pode cancelar sua assinatura a qualquer momento, sem penalidades ou explicações. Sem compromisso!</p>
                </details>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section id="cta" class="py-20 px-4 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-black mb-6">Pronto para criar seu website?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Junte-se a milhares de empreendedores que já têm seus sites profissionais funcionando!
            </p>
            
            <div class="flex gap-4 justify-center flex-wrap">
                <a href={{route('register')}} class="bg-white text-blue-600 px-10 py-4 rounded-lg font-bold text-lg hover:bg-blue-50 transition transform hover:scale-105">
                    <i class="fas fa-star mr-2"></i> Começar Grátis (14 dias)
                </a>
                <button class="border-2 border-white text-white px-10 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-blue-600 transition transform hover:scale-105">
                    <i class="fas fa-calendar mr-2"></i> Agendar Demo
                </button>
            </div>

            <p class="text-blue-100 text-sm mt-6">
                <i class="fas fa-lock mr-2"></i> Pagamento seguro • <i class="fas fa-credit-card mr-2"></i> Múltiplas formas de pagamento
            </p>
        </div>
    </section>

    <!-- Footer -->
    <x-landing-footer />

    <!-- Scripts inline (sem dependência de Vite) -->
    <script>
        function scrollToSection(id) {
            const element = document.getElementById(id);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        }

        // Smooth scroll para links internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = this.getAttribute('href');
                scrollToSection(target.substring(1));
            });
        });

        // Animações ao scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-up');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('[class*="card-hover"]').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>