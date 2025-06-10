@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-orange-50 to-pink-100">
  <!-- Header -->
  <header class="bg-white/80 backdrop-blur-md border-b border-pink-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center py-4">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 bg-gradient-to-r from-pink-500 to-orange-400 rounded transform rotate-12"></div>
          <span class="text-xl font-bold text-gray-800">LinkShrink</span>
        </div>
        <div class="flex items-center gap-4">
          <span class="text-gray-700 font-medium">Olá, {{ Auth::user()->name ?? 'Usuário' }}</span>
          <form method="POST">
            @csrf
            <button type="submit" class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors" title="Sair">
              <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-log-out" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M15 3h4a2 2 0 0 1 2 2v4M10 17l5-5-5-5M21 12H3" />
              </svg>
            </button>
          </form>
        </div>
      </div>
    </div>
  </header>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Encurtador de Links -->
    <div class="bg-white rounded-3xl shadow-xl p-8 mb-8 border border-pink-100">
      <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Encurtar Novo Link</h1>
        <p class="text-gray-600">Transforme seus links longos em URLs curtas e compartilháveis</p>
      </div>
      <form method="POST"  class="max-w-2xl mx-auto">
        @csrf
        <div class="flex gap-4">
          <input
            type="url"
            name="link"
            required
            placeholder="Cole seu link aqui (ex: https://...)"
            class="flex-1 px-4 py-3 border border-pink-200 rounded-xl focus:ring-2 focus:ring-pink-500 focus:border-transparent transition-all duration-200 bg-pink-50 focus:bg-white text-gray-800 placeholder:text-pink-400"
          />
          <button
            type="submit"
            class="bg-gradient-to-r from-pink-500 to-orange-400 text-white font-semibold py-3 px-8 rounded-xl hover:from-pink-600 hover:to-orange-500 transition-all duration-200 transform hover:scale-[1.02] whitespace-nowrap"
          >
            Encurtar
          </button>
        </div>
      </form>
      @if(session('shortUrl'))
      <div class="mt-6 p-4 bg-gradient-to-r from-green-50 to-pink-50 rounded-2xl border border-green-200">
        <div class="text-center">
          <p class="text-green-700 font-semibold mb-2">Link encurtado com sucesso!</p>
          <div class="flex items-center justify-center gap-2 bg-white rounded-lg p-3 border">
            <a href="{{ session('shortUrl') }}" class="text-pink-600 hover:underline truncate" target="_blank" rel="noopener noreferrer">
              {{ session('shortUrl') }}
            </a>
            <button onclick="navigator.clipboard.writeText('{{ session('shortUrl') }}');" class="text-pink-500 hover:text-pink-700 p-1 rounded hover:bg-pink-50" title="Copiar">
              <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-copy" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <rect x="9" y="9" width="13" height="13" rx="2" /><rect x="3" y="3" width="13" height="13" rx="2" />
              </svg>
            </button>
          </div>
        </div>
      </div>
      @endif
    </div>

    <!-- Dashboard de Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-2xl shadow-lg p-6 border border-pink-100">
        <div class="flex items-center">
          <div class="p-3 bg-pink-100 rounded-xl">
            <svg class="h-6 w-6 text-pink-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M3 12h18M12 3v18" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total de Links</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalLinks ?? 0 }}</p>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-lg p-6 border border-pink-100">
        <div class="flex items-center">
          <div class="p-3 bg-green-100 rounded-xl">
            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M9 12l2 2 4-4" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total de Cliques</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalClicks ?? 0 }}</p>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-lg p-6 border border-pink-100">
        <div class="flex items-center">
          <div class="p-3 bg-orange-100 rounded-xl">
            <svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M4 12h16M12 4v16" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Média de Cliques</p>
            <p class="text-2xl font-bold text-gray-800">{{ $mediaCliques ?? 0 }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Histórico de Links -->
    <div class="bg-white rounded-3xl shadow-xl border border-pink-100">
      <div class="p-6 border-b border-pink-100">
        <div class="flex justify-between items-center">
          <h2 class="text-2xl font-bold text-gray-800">Histórico de Links</h2>
          @if(!empty($links) && count($links) > 0)
          <form method="POST" action="{{ route('links.limpar') }}">
            @csrf
            <button type="submit" class="flex items-center gap-2 text-red-500 hover:text-red-700 hover:bg-red-50 px-4 py-2 rounded-lg transition-colors">
              <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-trash-2" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M3 6h18M9 6v12a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2V6m-6 0V4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />
              </svg>
              Limpar histórico
            </button>
          </form>
          @endif
        </div>
      </div>
      @if(empty($links) || count($links) === 0)
      <div class="p-12 text-center">
        <svg class="h-16 w-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M3 12h18M12 3v18" />
        </svg>
        <p class="text-gray-500 text-lg">Nenhum link encurtado ainda</p>
        <p class="text-gray-400">Seus links aparecerão aqui após serem criados</p>
      </div>
      @else
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-pink-100">
          <thead>
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link Original</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link Encurtado</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cliques</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Data de Criação</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-pink-50">
            @foreach($links as $link)
            <tr class="hover:bg-pink-50">
              <td class="px-6 py-4 whitespace-nowrap max-w-xs truncate">
                <div class="flex items-center gap-2">
                  <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 12h18M12 3v18" />
                  </svg>
                  <span title="{{ $link->original_url }}">{{ $link->original_url }}</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap max-w-xs truncate">
                <a href="{{ $link->short_url }}" target="_blank" class="text-pink-600 hover:text-pink-800 hover:underline truncate" title="Clique para visitar">
                  {{ $link->short_url }}
                </a>
              </td>
              <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-1">
                  <svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4" />
                  </svg>
                  <span class="font-semibold text-green-600">{{ $link->clicks }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-1">
                  <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M4 12h16M12 4v16" />
                  </svg>
                  <span class="text-gray-600">{{ \Carbon\Carbon::parse($link->created_at)->format('d/m/Y') }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-center">
                <button onclick="navigator.clipboard.writeText('{{ $link->short_url }}');" class="text-gray-500 hover:text-pink-600 p-2 rounded-lg hover:bg-pink-50 transition-colors" title="Copiar link">
                  <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-copy" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <rect x="9" y="9" width="13" height="13" rx="2" /><rect x="3" y="3" width="13" height="13" rx="2" />
                  </svg>
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
