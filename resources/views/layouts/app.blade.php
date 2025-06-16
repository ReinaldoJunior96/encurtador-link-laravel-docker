<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Encurtador de Link' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="antialiased text-gray-800">
@auth
        <div style="background: #ffe0e0; color: #c00; padding: 8px; text-align:center;">
            Logado como: {{ Auth::user()->email }} | Role: {{ Auth::user()->role }}
        </div>
    @endauth
    {{-- Conteúdo da página --}}
    @yield('content')

    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <!-- Mobile menu button-->
                </div>
                <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="hidden sm:block sm:ml-6">
                        <div class="flex space-x-4">
                            <a href="/" class="text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Início</a>
                            <a href="/sobre" class="text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Sobre</a>
                            <a href="/contato" class="text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Contato</a>
                            <a href="/rh" class="text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Gestão</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

</body>
</html>
