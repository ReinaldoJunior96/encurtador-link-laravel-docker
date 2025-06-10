<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LinkShrink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-pink-50 via-orange-50 to-pink-100 flex items-center justify-center">
    <div class="w-full max-w-md bg-white/90 rounded-2xl shadow-2xl p-8 border border-pink-100 backdrop-blur-md">
        <div class="flex flex-col items-center mb-6">
            <div class="w-14 h-14 bg-gradient-to-r from-pink-500 to-orange-400 rounded-full flex items-center justify-center mb-2 animate-bounce">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 21a4 4 0 01-5.656 0l-5.172-5.172a4 4 0 010-5.656l7.07-7.071a4 4 0 015.657 0l5.172 5.172a4 4 0 010 5.657l-7.071 7.07z" />
                </svg>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-800">Bem-vindo de volta!</h1>
            <p class="text-gray-500 text-sm mt-1">Faça login para acessar seu painel</p>
        </div>
        <form method="POST" action="/login" class="space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
                <input type="email" name="email" id="email" required autofocus placeholder="seu@email.com"
                    class="w-full px-4 py-3 border border-pink-200 rounded-lg focus:ring-2 focus:ring-pink-400 focus:outline-none transition bg-pink-50 text-gray-800 placeholder:text-pink-400" />
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
                <input type="password" name="password" id="password" required placeholder="••••••••"
                    class="w-full px-4 py-3 border border-pink-200 rounded-lg focus:ring-2 focus:ring-pink-400 focus:outline-none transition bg-pink-50 text-gray-800 placeholder:text-pink-400" />
            </div>
            <div class="flex items-center justify-between">
                <label class="flex items-center text-sm">
                    <input type="checkbox" name="remember" class="rounded border-pink-300 text-pink-600 shadow-sm focus:ring-pink-500">
                    <span class="ml-2 text-gray-600">Lembrar de mim</span>
                </label>
                <a href="#" class="text-pink-600 hover:underline text-sm">Esqueceu a senha?</a>
            </div>
            <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-pink-500 to-orange-400 hover:from-pink-600 hover:to-orange-500 text-white font-bold rounded-lg shadow-lg transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2">
                <span>Entrar</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-arrow-right" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </button>
        </form>
        <div class="mt-8 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} LinkShrink. Todos os direitos reservados.
        </div>
    </div>
</body>
</html>
