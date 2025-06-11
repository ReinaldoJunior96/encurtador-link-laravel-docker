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

</body>
</html>
