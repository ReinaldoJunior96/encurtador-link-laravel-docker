@extends('layouts.app')

@section('content')
   <div class="min-h-screen bg-gradient-to-br from-pink-50 via-orange-50 to-pink-100">
      <!-- Header/Navbar igual ao dashboard -->
      <header class="bg-white/80 backdrop-blur-md border-b border-pink-100 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center py-4">
            <div class="flex items-center gap-2">
               <div class="w-8 h-8 bg-gradient-to-r from-pink-500 to-orange-400 rounded transform rotate-12"></div>
               <span class="text-xl font-bold text-gray-800">Formulários</span>
            </div>
            <div class="flex items-center gap-4">
               <a href="/dashboard"
                 class="text-pink-600 hover:text-pink-800 font-semibold px-3 py-2 rounded-lg hover:bg-pink-50 transition">Dashboard</a>
               <a href="/rh/formulario"
                 class="text-orange-500 hover:text-orange-700 font-semibold px-3 py-2 rounded-lg hover:bg-orange-50 transition">Formulários</a>
               <a href="/chat"
                 class="text-pink-600 hover:text-pink-800 font-semibold px-3 py-2 rounded-lg hover:bg-pink-50 transition">Chat</a>
               <span class="text-gray-700 font-medium">Olá, {{ Auth::user()->name ?? 'Usuário' }}</span>
               <form method="POST" action="{{ route('logout') }}" class="inline">
                 @csrf
                 <button type="submit"
                   class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors"
                   title="Sair">
                   <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-log-out" width="20" height="20"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path d="M15 3h4a2 2 0 0 1 2 2v4M10 17l5-5-5-5M21 12H3" />
                   </svg>
                 </button>
               </form>
            </div>
          </div>
        </div>
      </header>
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="w-full  mx-auto bg-white/90 rounded-2xl shadow-2xl p-8 border border-pink-100 backdrop-blur-md">
          <div
            class="mb-8 p-6 bg-gradient-to-br from-pink-50 to-orange-50 rounded-xl border border-pink-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
               <div class="bg-pink-100 rounded-full p-3 flex items-center justify-center shadow">
                 <img src="/favicon.ico" alt="Logo" class="h-8 w-8" />
               </div>
               <div>
                 <div class="text-lg font-bold text-pink-600">Crie, edite e compartilhe formulários de forma intuitiva
                 </div>
                 <div class="text-xs text-gray-500">Arraste, edite perguntas, visualize respostas e compartilhe com
                   facilidade, como no Google Forms.</div>
               </div>
            </div>
            <a href="{{ route('rh.formulario.create') }}"
               class="px-6 py-3 bg-orange-400 text-white rounded-lg font-bold shadow hover:bg-orange-500 transition text-lg flex items-center gap-2"><svg
                 xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
               </svg> Novo Formulário</a>
          </div>
          <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
               <span class="text-pink-600 font-semibold text-lg">Meus Formulários</span>
               <input type="text" id="buscaForm" placeholder="Buscar formulário..."
                 class="px-3 py-2 border border-pink-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-300 text-sm w-64" />
            </div>
            <div class="overflow-x-auto rounded-xl border border-pink-100 bg-white shadow">
               <table class="min-w-full" id="tabelaFormularios">
                 <thead>
                   <tr class="bg-pink-50 text-pink-600">
                     <th class="px-4 py-2 text-left">Título</th>
                     <th class="px-4 py-2 text-left">Criado em</th>
                     <th class="px-4 py-2 text-left">Ações</th>
                   </tr>
                 </thead>
                 <tbody>
                   @forelse($formularios as $form)
                  <tr class="border-b border-pink-100 hover:bg-orange-50 transition">
                   <td class="px-4 py-2 font-semibold text-gray-800">{{ $form->titulo }}</td>
                   <td class="px-4 py-2 text-gray-500">{{ $form->created_at->format('d/m/Y H:i') }}</td>
                   <td class="px-4 py-2 flex flex-wrap gap-2">
                     <a href="{{ route('rh.formulario.edit', $form->hashSlug) }}"
                       class="px-3 py-1 bg-pink-400 text-white rounded-lg font-bold shadow hover:bg-pink-500 transition text-xs flex items-center gap-1"><svg
                        xmlns='http://www.w3.org/2000/svg' class='h-4 w-4' fill='none' viewBox='0 0 24 24'
                        stroke='currentColor'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                          d='M15.232 5.232l3.536 3.536M9 13h3l8-8a2.828 2.828 0 00-4-4l-8 8v3h3z' />
                       </svg> Editar</a>
                     <a href="{{ route('formulario.public', $form->hashSlug) }}" target="_blank"
                       class="px-3 py-1 bg-orange-400 text-white rounded-lg font-bold shadow hover:bg-orange-500 transition text-xs flex items-center gap-1"><svg
                        xmlns='http://www.w3.org/2000/svg' class='h-4 w-4' fill='none' viewBox='0 0 24 24'
                        stroke='currentColor'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                          d='M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9' />
                       </svg> Compartilhar</a>
                     <a href="{{ route('rh.formulario.metricas', $form->hashSlug) }}"
                       class="px-3 py-1 bg-pink-600 text-white rounded-lg font-bold shadow hover:bg-pink-700 transition text-xs flex items-center gap-1"><svg
                        xmlns='http://www.w3.org/2000/svg' class='h-4 w-4' fill='none' viewBox='0 0 24 24'
                        stroke='currentColor'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                          d='M11 17a2 2 0 104 0 2 2 0 00-4 0zm-7-2a2 2 0 104 0 2 2 0 00-4 0zm14 0a2 2 0 104 0 2 2 0 00-4 0z' />
                       </svg> Métricas</a>
                     <button type="button"
                       onclick="navigator.clipboard.writeText('{{ url(route('formulario.public', $form->hashSlug, false)) }}');this.textContent='Copiado!';setTimeout(()=>this.textContent='Copiar Link',1500)"
                       class="px-3 py-1 bg-pink-200 text-pink-700 rounded-lg font-bold shadow hover:bg-pink-300 transition text-xs flex items-center gap-1"><svg
                        xmlns='http://www.w3.org/2000/svg' class='h-4 w-4' fill='none' viewBox='0 0 24 24'
                        stroke='currentColor'>
                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                          d='M8 16h8M8 12h8m-7 8h6a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z' />
                       </svg> Copiar Link</button>
                     <form method="POST" action="{{ route('rh.formulario.destroy', $form->hashSlug) }}"
                       onsubmit="return confirm('Tem certeza?')">
                       @csrf
                       @method('DELETE')
                       <button type="submit"
                        class="px-3 py-1 bg-red-500 text-white rounded-lg font-bold shadow hover:bg-red-600 transition text-xs flex items-center gap-1"><svg
                          xmlns='http://www.w3.org/2000/svg' class='h-4 w-4' fill='none' viewBox='0 0 24 24'
                          stroke='currentColor'>
                          <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2'
                            d='M6 18L18 6M6 6l12 12' />
                        </svg> Excluir</button>
                     </form>
                   </td>
                  </tr>
               @empty
                  <tr>
                   <td colspan="3" class="text-center text-gray-400 py-8">Nenhum formulário cadastrado.</td>
                  </tr>
               @endforelse
                 </tbody>
               </table>
            </div>
          </div>
        </div>
      </div>
   </div>
   <script>
      document.getElementById('buscaForm').addEventListener('input', function () {
        const termo = this.value.toLowerCase();
        const linhas = document.querySelectorAll('#tabelaFormularios tbody tr');
        linhas.forEach(linha => {
          let textoLinha = linha.innerText.toLowerCase();
          linha.style.display = textoLinha.includes(termo) ? '' : 'none';
        });
      });
   </script>
@endsection