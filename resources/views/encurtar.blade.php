<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encurtador de Link - Checkout</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-200 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 border border-blue-100">
        {{-- <div class="flex flex-col items-center mb-6">
            <svg class="w-12 h-12 text-blue-600 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 21a4 4 0 01-5.656 0l-5.172-5.172a4 4 0 010-5.656l7.07-7.071a4 4 0 015.657 0l5.172 5.172a4 4 0 010 5.657l-7.071 7.07z" /></svg>
            <h1 class="text-2xl font-bold text-blue-700">Encurtador de Link</h1>
            <p class="text-gray-500 text-sm mt-1">Pague e gere seu link encurtado em segundos!</p>
        </div> --}}
        <form method="GET" action="/api/checkout" class="space-y-6">
            <div>
                <label for="url" class="block text-sm font-medium text-gray-700 mb-1">Cole o link que deseja encurtar</label>
                <input type="url" name="url" id="url" required placeholder="https://exemplo.com" class="w-full px-4 py-2 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none transition" />
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">Pagar e Encurtar</button>
        </form>
        <div class="mt-8 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} Encurtador de Link. Todos os direitos reservados.
        </div>
    </div>
</body>
</html>
