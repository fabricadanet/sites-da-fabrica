<!-- resources/views/auth/register.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sites da Fábrica') }} - Criar Conta</title>
    
    <meta name="description" content="Crie sua conta gratuita e comece a construir seu website profissional">
    
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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

        .animate-fade-up {
            animation: fadeInUp 0.8s ease-out;
        }

        .gradient-primary {
            background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #3B82F6, #8B5CF6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
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

        .input-field {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            outline: none;
            border-color: #3B82F6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .error-text {
            color: #EF4444;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .sticky-header {
            position: sticky;
            top: 0;
            background: white;
            z-index: 50;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .link-hover {
            transition: color 0.3s ease;
        }

        .link-hover:hover {
            color: #1E40AF;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50">

    <!-- Header/Navigation -->
    <header class="sticky-header">
        <nav class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-3 text-decoration-none">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                    <i class="fas fa-globe text-white text-lg"></i>
                </div>
                <span class="text-xl font-bold text-gray-900">{{ config('app.name') }}</span>
            </a>
            <div>
                <p class="text-gray-600">Já tem conta? 
                    <a href="{{ route('login') }}" class="text-blue-600 font-semibold link-hover">Faça login</a>
                </p>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md animate-fade-up">
            
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-rocket text-white text-3xl"></i>
                </div>
                <h1 class="text-3xl font-black text-gray-900">Criar Conta</h1>
                <p class="text-gray-600 mt-2">Junte-se aos milhares de empreendedores</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100">
                
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                        <p class="text-red-800 font-semibold mb-2">❌ Erros no formulário:</p>
                        <ul class="text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-blue-600"></i>Nome Completo
                        </label>
                        <input 
                            id="name" 
                            class="input-field @error('name') border-red-500 @enderror" 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus
                            placeholder="João Silva"
                        />
                        @error('name')
                            <p class="error-text"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2 text-blue-600"></i>Email
                        </label>
                        <input 
                            id="email" 
                            class="input-field @error('email') border-red-500 @enderror" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required
                            placeholder="seu@email.com"
                        />
                        @error('email')
                            <p class="error-text"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-600"></i>Senha
                        </label>
                        <input 
                            id="password" 
                            class="input-field @error('password') border-red-500 @enderror" 
                            type="password" 
                            name="password" 
                            required
                            placeholder="••••••••"
                        />
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-info-circle mr-1"></i>Mínimo 8 caracteres, com letras, números e símbolos
                        </p>
                        @error('password')
                            <p class="error-text"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-blue-600"></i>Confirmar Senha
                        </label>
                        <input 
                            id="password_confirmation" 
                            class="input-field" 
                            type="password" 
                            name="password_confirmation" 
                            required
                            placeholder="••••••••"
                        />
                    </div>

                    <!-- Terms -->
                    <div class="flex items-start pt-2">
                        <input 
                            type="checkbox" 
                            id="terms" 
                            name="terms" 
                            class="mt-1 w-4 h-4 rounded accent-blue-600"
                            required
                        />
                        <label for="terms" class="ml-3 text-sm text-gray-600">
                            Concordo com os 
                            <a href="#" class="text-blue-600 font-semibold link-hover">Termos de Serviço</a>
                            e
                            <a href="#" class="text-blue-600 font-semibold link-hover">Política de Privacidade</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="btn-primary w-full py-3 text-lg font-bold mt-6"
                    >
                        <i class="fas fa-check mr-2"></i> Criar Conta Grátis
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">ou</span>
                    </div>
                </div>

                <!-- Login Link -->
                <p class="text-center text-gray-600">
                    Já tem uma conta? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-bold link-hover">
                        Faça login aqui
                    </a>
                </p>
            </div>

            <!-- Benefits -->
            <div class="mt-8 grid grid-cols-3 gap-4 px-2">
                <div class="text-center">
                    <div class="text-3xl mb-2">✓</div>
                    <p class="text-sm text-gray-600 font-semibold">Sem cartão</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl mb-2">14</div>
                    <p class="text-sm text-gray-600 font-semibold">Dias grátis</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl mb-2">∞</div>
                    <p class="text-sm text-gray-600 font-semibold">Cancele quando quiser</p>
                </div>
            </div>

            <!-- CTA Banner -->
            <div class="mt-8 bg-gradient-to-r from-blue-100 to-purple-100 p-6 rounded-xl border border-blue-200">
                <p class="text-sm text-gray-700 text-center">
                    <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                    <strong>Pagamento 100% seguro</strong> com SSL incluído
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-16 py-8 border-t border-gray-200 bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-400 text-sm">
            <p>&copy; 2024 {{ config('app.name') }}. Todos os direitos reservados.</p>
        </div>
    </footer>

</body>
</html>