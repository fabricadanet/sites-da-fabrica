<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome (opcional) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- 1️⃣ Tailwind e app.js via Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- 2️⃣ Livewire Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            <!-- Navigation -->
            @include('layouts.navigation')

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- 3️⃣ Livewire Scripts (IMPORTANTE: vem antes de scripts custom) -->
        @livewireScripts

        <!-- 4️⃣ Scripts customizados (vem DEPOIS de Livewire) -->
        <script>
            // Aqui você pode usar Livewire
            console.log('✅ Livewire disponível:', typeof Livewire !== 'undefined');
        </script>

        @stack('scripts')
    </body>
</html>