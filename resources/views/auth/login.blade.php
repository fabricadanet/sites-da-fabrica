<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sites da Fábrica') }} - Login</title>
    
    <meta name="description" content="Faça login na sua conta e acesse seu dashboard">
    
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

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-up {
            animation: fadeInUp 0.8s ease-out;
        }

        .animate-slide-in-right {
            animation: slideInRight 0.8s ease-out 0.2s both;
        }

        .gradient-primary {
            background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%);
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

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-secondary {
            background: white;
            color: #3B82F6;
            border: 2px solid #3B82F6;
            padding: 10px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #3B82F6;
            color: white;
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

        .checkbox-custom {
            accent-color: #3B82F6;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50">

    <!-- Header/Navigation -->
    <header class="sticky-header">
        <nav class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                    <i class="fas fa-globe text-white text-lg"></i>
                </div>
                <span class="text-xl font-bold text-gray-900">{{ config('app.name') }}</span>
            </a>
            <div>
                <p class="text-gray-600">Novo por aqui? 
                    <a href="{{ route('register') }}" class="text-blue-600 font-semibold link-hover">Crie sua conta</a>
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
                    <i class="fas fa-sign-in-alt text-white text-3xl"></i>
                </div>
                <h1 class="text-3xl font-black text-gray-900">Bem-vindo de volta!</h1>
                <p class="text-gray-600 mt-2">Faça login para acessar seus sites</p>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 border border-gray-100 animate-slide-in-right">
                
                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded">
                        <p class="text-green-800">
                            <i class="fas fa-check-circle mr-2"></i>{{ session('status') }}
                        </p>
                    </div>
                @endif

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded">
                        <p class="text-red-800 font-semibold mb-2">❌ Erro ao fazer login:</p>
                        <ul class="text-red-700 text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

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
                            autofocus
                            placeholder="seu@email.com"
                        />
                        @error('email')
                            <p class="error-text"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-lock mr-2 text-blue-600"></i>Senha
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 font-semibold link-hover">
                                Esqueceu?
                            </a>
                        </div>
                        <input 
                            id="password" 
                            class="input-field @error('password') border-red-500 @enderror" 
                            type="password" 
                            name="password" 
                            required
                            placeholder="••••••••"
                        />
                        @error('password')
                            <p class="error-text"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            name="remember" 
                            class="checkbox-custom w-4 h-4 rounded"
                        />
                        <label for="remember" class="ml-3 text-sm text-gray-600">
                            Lembrar de mim por 30 dias
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="btn-primary w-full py-3 text-lg font-bold"
                    >
                        <i class="fas fa-arrow-right mr-2"></i> Fazer Login
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

                <!-- Register Link -->
                <p class="text-center text-gray-600 mb-4">
                    Não tem uma conta? 
                    <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-bold link-hover">
                        Crie uma agora
                    </a>
                </p>

                <!-- Back to Landing -->
                <p class="text-center">
                    <a href="{{ route('landing') }}" class="text-gray-600 font-semibold link-hover text-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Voltar para página inicial
                    </a>
                </p>
            </div>

            <!-- Security Banner -->
            <div class="mt-8 bg-gradient-to-r from-blue-100 to-purple-100 p-6 rounded-xl border border-blue-200">
                <div class="flex gap-3">
                    <div>
                        <i class="fas fa-lock-open text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-700">
                            <strong>Acesso 100% seguro</strong><br>
                            Seus dados estão protegidos com criptografia SSL/TLS de ponta.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="mt-8 grid grid-cols-2 gap-4 text-center text-sm">
                <div class="text-gray-600">
                    <p class="text-lg font-bold text-blue-600">✓</p>
                    <p class="text-xs">Acesso imediato</p>
                </div>
                <div class="text-gray-600">
                    <p class="text-lg font-bold text-blue-600">✓</p>
                    <p class="text-xs">Múltiplos sites</p>
                </div>
                <div class="text-gray-600">
                    <p class="text-lg font-bold text-blue-600">✓</p>
                    <p class="text-xs">Analytics em tempo real</p>
                </div>
                <div class="text-gray-600">
                    <p class="text-lg font-bold text-blue-600">✓</p>
                    <p class="text-xs">Suporte 24/7</p>
                </div>
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
