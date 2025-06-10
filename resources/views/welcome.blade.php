@extends('layouts.app') {{-- Supondo que você tenha um layout base --}}
@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-orange-50 to-pink-100">
  <!-- Header -->
  <header class="w-full px-6 py-4 flex justify-between items-center bg-white/80 backdrop-blur-sm border-b border-pink-100">
    <div class="flex items-center gap-2">
      <div class="w-8 h-8 bg-gradient-to-r from-pink-500 to-orange-400 rounded transform rotate-12"></div>
      <span class="text-2xl font-bold text-gray-800">LinkShrink</span>
    </div>

    <nav class="hidden md:flex items-center gap-8">
      <a href="#home" class="text-gray-700 hover:text-pink-600 transition-colors">Home</a>
      <a href="#services" class="text-gray-700 hover:text-pink-600 transition-colors">Serviços</a>
      <a href="#pricing" class="text-gray-700 hover:text-pink-600 transition-colors">Preços</a>
      <a href="#contact" class="text-gray-700 hover:text-pink-600 transition-colors">Contato</a>
    </nav>

    <a href="{{ route('login') }}" class="flex items-center gap-2 bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition-colors">
      <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-log-in" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path d="M15 3h4a2 2 0 0 1 2 2v4M10 17l5-5-5-5M21 12H3" />
      </svg>
      <span>Login</span>
    </a>
  </header>

  <!-- Main Section -->
  <main class="container mx-auto px-6 py-16">
    <div class="flex flex-col lg:flex-row items-center gap-12">
      <!-- Left Side - Content and Form -->
      <div class="flex-1 max-w-lg">
        <div class="">
          <h1 class="text-5xl lg:text-4xl font-bold text-gray-800 mb-3 leading-[110%]">
         Transforme Links em Resultados <br />
         
        </h1>
         <p class="text-pink-600 text-5xl lg:text-2xl font-bold leading-[130%] mb-2" style=""> Encurte, Compartilhe e Analise com Facilidade</p>
        </div>
       

        <!-- Link Shortening Form -->
        <div class="bg-white rounded-2xl shadow-xl p-6 border border-pink-100">
          <form method="POST"  class="space-y-4" onsubmit="return validateLink(this)">
            @csrf
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Cole seu link aqui:</label>
              <input
                type="url"
                name="link"
                placeholder="https://exemplo.com/seu-link-muito-longo"
                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-transparent outline-none text-gray-800"
                required
              />
            </div>

            <button
              type="submit"
              class="w-full bg-gradient-to-r from-pink-500 to-orange-400 text-white font-semibold py-3 px-6 rounded-lg hover:from-pink-600 hover:to-orange-500 transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2 shadow-lg"
            >
              <span>Pagar e Encurtar</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-arrow-right" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M5 12h14M12 5l7 7-7 7"/>
              </svg>
            </button>
          </form>
        </div>

        <div class="flex gap-4 mt-8">
          <button class="bg-pink-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-pink-700 transition-colors shadow-lg">COMEÇAR AGORA</button>
          <button class="border-2 border-pink-300 text-pink-600 px-8 py-3 rounded-lg font-semibold hover:bg-pink-50 transition-colors">SAIBA MAIS</button>
        </div>
      </div>

      <!-- Right Side - Illustration -->
      <div class="flex-1 relative">
        <div class="relative w-full max-w-md mx-auto">
          <div class="absolute inset-0 bg-gradient-to-br from-pink-200 to-orange-200 rounded-full opacity-50 transform rotate-6"></div>

          <div class="relative bg-gradient-to-br from-pink-500 to-orange-400 rounded-3xl p-8 shadow-2xl transform -rotate-3 hover:rotate-0 transition-transform duration-500">
            <div class="bg-white rounded-2xl p-6 shadow-inner">
              <div class="flex items-center gap-2 mb-4">
                <svg class="text-pink-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path d="M10 13a5 5 0 0 0 7.071 0l1.414-1.414a5 5 0 0 0 0-7.071M14 11a5 5 0 0 1-7.071 0l-1.414-1.414a5 5 0 0 1 0-7.071"/>
                </svg>
                <span class="font-bold text-gray-800">LinkShrink</span>
              </div>

              <div class="space-y-3">
                <div class="bg-gray-100 rounded-lg p-3">
                  <div class="h-2 bg-pink-300 rounded w-3/4 mb-2"></div>
                  <div class="h-2 bg-gray-300 rounded w-1/2"></div>
                </div>
                <div class="bg-gray-100 rounded-lg p-3">
                  <div class="h-2 bg-orange-300 rounded w-2/3 mb-2"></div>
                  <div class="h-2 bg-gray-300 rounded w-3/5"></div>
                </div>
                <div class="bg-pink-100 rounded-lg p-3 border-2 border-pink-300">
                  <div class="h-2 bg-pink-500 rounded w-1/2 mb-2"></div>
                  <div class="h-2 bg-pink-300 rounded w-2/3"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="absolute -top-4 -right-4 bg-white rounded-full p-3 shadow-lg">
            <div class="w-6 h-6 bg-gradient-to-r from-pink-500 to-orange-400 rounded-full"></div>
          </div>

          <div class="absolute -bottom-6 -left-6 bg-white rounded-full p-4 shadow-lg">
            <svg class="text-pink-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path d="M10 13a5 5 0 0 0 7.071 0l1.414-1.414a5 5 0 0 0 0-7.071M14 11a5 5 0 0 1-7.071 0l-1.414-1.414a5 5 0 0 1 0-7.071"/>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Seção de Preços -->
    <section id="pricing" class="mt-24">
      <h2 class="text-3xl font-bold text-center text-gray-800 mb-4">Planos e Preços</h2>
      <p class="text-center text-lg text-pink-600 mb-10">Escolha o pacote ideal para você e comece a encurtar seus links agora mesmo!</p>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
        <!-- Card 1 -->
        <div class="bg-white rounded-2xl shadow-xl border border-pink-100 flex flex-col items-center p-8 transition-transform hover:scale-105">
          <span class="text-pink-500 font-semibold text-lg mb-2">Link Único</span>
          <div class="text-4xl font-extrabold text-gray-800 mb-2">R$ 4,90</div>
          <div class="text-gray-500 text-sm mb-4">por link</div>
          <p class="text-gray-700 mb-6 text-center">Ideal para quem precisa encurtar apenas um link. Simples, rápido e sem complicações.</p>
          <button class="w-full bg-gradient-to-r from-pink-500 to-orange-400 text-white font-semibold py-3 px-6 rounded-lg hover:from-pink-600 hover:to-orange-500 transition-all duration-300 shadow-lg">Encurtar Agora</button>
        </div>
        <!-- Card 2 -->
        <div class="bg-white rounded-2xl shadow-2xl border-2 border-pink-400 flex flex-col items-center p-8 scale-105 z-10 relative">
          <span class="text-orange-500 font-semibold text-lg mb-2">Pacote 5 Links</span>
          <div class="text-4xl font-extrabold text-gray-800 mb-2">R$ 19,90</div>
          <div class="text-gray-500 text-sm mb-4">até 5 links</div>
          <p class="text-gray-700 mb-6 text-center">Economize! Encurte até 5 links com desconto exclusivo. Perfeito para campanhas ou múltiplos projetos.</p>
          <button class="w-full bg-gradient-to-r from-orange-400 to-pink-500 text-white font-semibold py-3 px-6 rounded-lg hover:from-orange-500 hover:to-pink-600 transition-all duration-300 shadow-lg">Aproveitar Desconto</button>
          <span class="absolute -top-4 right-4 bg-pink-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow">Mais Popular</span>
        </div>
        <!-- Card 3 -->
        <div class="bg-white rounded-2xl shadow-xl border border-pink-100 flex flex-col items-center p-8 transition-transform hover:scale-105">
          <span class="text-pink-500 font-semibold text-lg mb-2">Pacote 10 Links</span>
          <div class="text-4xl font-extrabold text-gray-800 mb-2">R$ 34,90</div>
          <div class="text-gray-500 text-sm mb-4">até 10 links</div>
          <p class="text-gray-700 mb-6 text-center">Mais economia para você! Encurte até 10 links com o melhor custo-benefício. Ideal para empresas e agências.</p>
          <button class="w-full bg-gradient-to-r from-pink-500 to-orange-400 text-white font-semibold py-3 px-6 rounded-lg hover:from-pink-600 hover:to-orange-500 transition-all duration-300 shadow-lg">Quero este Plano</button>
        </div>
      </div>
    </section>
  </main>
</div>

<script>
  function validateLink(form) {
    const input = form.link.value;
    if (!input.startsWith('http://') && !input.startsWith('https://')) {
      alert("Por favor, insira um link válido, incluindo http:// ou https://");
      return false;
    }
    return true;
  }
</script>
@endsection
