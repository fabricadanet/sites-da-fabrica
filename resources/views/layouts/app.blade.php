<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Sites da Fábrica') }} - Dashboard</title>
    
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

        body {
            background: #F9FAFB;
        }

        .sidebar {
            background: white;
            border-right: 1px solid #E5E7EB;
        }

        .sidebar-item {
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-item:hover {
            background: #F3F4F6;
        }

        .sidebar-item.active {
            background: #EFF6FF;
            border-left-color: #3B82F6;
            color: #3B82F6;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid #E5E7EB;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3B82F6, #1E40AF);
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: #6B7280;
            padding: 8px 16px;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #F9FAFB;
            border-color: #D1D5DB;
        }

        .card {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            z-index: 50;
        }

        .dropdown-menu.active {
            display: block;
        }

        .dropdown-item {
            padding: 12px 16px;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: #F3F4F6;
            color: #3B82F6;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-primary {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .badge-success {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-warning {
            background: #FEF3C7;
            color: #92400E;
        }

        .badge-danger {
            background: #FEE2E2;
            color: #991B1B;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="flex h-screen bg-gray-50">
        
        <!-- Sidebar -->
        <aside class="sidebar w-64 hidden md:flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center">
                        <i class="fas fa-globe text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-900">{{ config('app.name') }}</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-8 space-y-2">
                <a href="{{ route('dashboard') }}" class="sidebar-item active px-4 py-2 rounded-lg flex items-center gap-3">
                    <i class="fas fa-home text-lg"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="sidebar-item px-4 py-2 rounded-lg flex items-center gap-3 text-gray-600 hover:text-gray-900">
                    <i class="fas fa-globe text-lg"></i>
                    <span>Meus Sites</span>
                </a>
                <a href="#" class="sidebar-item px-4 py-2 rounded-lg flex items-center gap-3 text-gray-600 hover:text-gray-900">
                    <i class="fas fa-chart-bar text-lg"></i>
                    <span>Analytics</span>
                </a>
                <a href="#" class="sidebar-item px-4 py-2 rounded-lg flex items-center gap-3 text-gray-600 hover:text-gray-900">
                    <i class="fas fa-cog text-lg"></i>
                    <span>Configurações</span>
                </a>
            </nav>

            <!-- Footer -->
            <div class="p-6 border-t border-gray-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full btn-secondary flex items-center justify-center gap-2">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Bar -->
            <header class="topbar px-6 h-16 flex items-center justify-between">
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-gray-600" id="mobileMenuBtn">
                    <i class="fas fa-bars text-2xl"></i>
                </button>

                <!-- Title (mobile) -->
                <h1 class="md:hidden text-xl font-bold text-gray-900">{{ config('app.name') }}</h1>

                <!-- Right Side -->
                <div class="flex items-center gap-4 ml-auto">
                    <!-- Notifications -->
                    <button class="relative text-gray-600 hover:text-gray-900" title="Notificações">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <!-- User Menu -->
                    <div class="relative">
                        <button 
                            class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-100 transition"
                            id="userMenuBtn"
                        >
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-700 rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-900 hidden sm:inline">
                                {{ Auth::user()->name }}
                            </span>
                            <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="dropdown-menu absolute right-0 mt-2 w-48" id="userMenu" style="top: 100%;">
                            <div class="dropdown-item border-b border-gray-100">
                                <div class="font-semibold text-gray-900">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                            </div>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-user-circle mr-2"></i> Perfil
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-cog mr-2"></i> Configurações
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-question-circle mr-2"></i> Ajuda
                            </a>
                            <form method="POST" action="{{ route('logout') }}" style="display: contents;">
                                @csrf
                                <button type="submit" class="dropdown-item w-full text-left border-t border-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-auto">
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        // User Menu Toggle
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userMenu = document.getElementById('userMenu');

        userMenuBtn?.addEventListener('click', () => {
            userMenu?.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!userMenuBtn?.contains(e.target) && !userMenu?.contains(e.target)) {
                userMenu?.classList.remove('active');
            }
        });

        // Mobile Menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        // Implementar mobile menu conforme necessário
    </script>

    @stack('scripts')
</body>
</html>
