<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sites da Fábrica') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- ⭐ VITE - CSS + APP.JS ⭐ -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- ⭐ LIVEWIRE STYLES ⭐ -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">

            <!-- Navigation -->
            @if (isset($header) || Route::currentRouteName() !== 'landing')
                @include('layouts.navigation')
            @endif

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- ⭐ LIVEWIRE SCRIPTS - CRÍTICO: VEM ANTES DE SCRIPTS CUSTOM ⭐ -->
        @livewireScripts
         <!-- Alpine.js OBRIGATÓRIO -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- SEU JS CUSTOM - VEM DEPOIS DO LIVEWIRE -->
        <script>
            // Agora Livewire está disponível
            document.addEventListener('DOMContentLoaded', function() {
                console.log('✅ Layout carregado');
                console.log('✅ Livewire:', typeof Livewire !== 'undefined' ? 'Disponível ✓' : 'Não disponível ✗');
            });

            // Ouvir eventos do Livewire
            if (typeof Livewire !== 'undefined') {
                Livewire.on('site-created', (siteId) => {
                    console.log('Site criado:', siteId);
                    location.reload();
                });
            }
        </script>

        @stack('scripts')
    </body>
</html>