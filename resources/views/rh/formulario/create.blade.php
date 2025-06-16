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
                    <a href="/dashboard" class="text-pink-600 hover:text-pink-800 font-semibold px-3 py-2 rounded-lg hover:bg-pink-50 transition">Dashboard</a>
                    <a href="/rh/formulario" class="text-orange-500 hover:text-orange-700 font-semibold px-3 py-2 rounded-lg hover:bg-orange-50 transition">Formulários</a>
                    <a href="/chat" class="text-pink-600 hover:text-pink-800 font-semibold px-3 py-2 rounded-lg hover:bg-pink-50 transition">Chat</a>
                    <span class="text-gray-700 font-medium">Olá, {{ Auth::user()->name ?? 'Usuário' }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
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
        <div class="w-full  mx-auto bg-white/90 rounded-2xl shadow-2xl p-8 border border-pink-100 backdrop-blur-md">
            <h1 class="text-2xl font-bold text-pink-600 mb-6 flex items-center gap-2">
                <svg xmlns='http://www.w3.org/2000/svg' class='h-8 w-8 text-orange-400' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4' /></svg>
                Novo Formulário
            </h1>
            <form method="POST" action="{{ route('rh.formulario.store') }}" id="form-estrutura">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-pink-500 mb-1">Título do Formulário</label>
                    <input type="text" name="titulo" required class="w-full px-4 py-2 rounded-lg border border-pink-200 focus:ring-2 focus:ring-pink-400 bg-white text-gray-800 placeholder:text-pink-400 text-lg font-semibold" placeholder="Ex: Pesquisa de Satisfação" />
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-orange-500 mb-2">Construa seu formulário</label>
                    <div id="campos-lista" class="flex flex-col gap-4"></div>
                    <button type="button" id="add-campo" class="mt-4 px-6 py-2 bg-orange-400 text-white rounded-lg font-bold shadow hover:bg-orange-500 transition flex items-center gap-2"><svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 4v16m8-8H4' /></svg> Adicionar Campo</button>
                </div>
                <input type="hidden" name="estrutura" id="estrutura-json" />
                <div class="flex gap-4 mt-8 justify-end">
                    <a href="{{ route('rh.formulario.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-bold shadow hover:bg-gray-300 transition">Cancelar</a>
                    <button type="submit" class="px-6 py-2 bg-pink-500 text-white rounded-lg font-bold shadow hover:bg-pink-600 transition">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
let campos = [];
let dragSrcIndex = null;
function renderCampos() {
    const lista = document.getElementById('campos-lista');
    lista.innerHTML = '';
    if(campos.length === 0) {
        lista.innerHTML = `<div class='text-center text-gray-400 italic py-8'>Adicione campos ao seu formulário usando o botão abaixo.</div>`;
    }
    campos.forEach((campo, i) => {
        const div = document.createElement('div');
        div.className = 'flex flex-col md:flex-row gap-2 items-center bg-pink-50 border border-pink-100 rounded-xl p-4 shadow-sm relative group cursor-move';
        div.setAttribute('draggable', 'true');
        div.setAttribute('data-index', i);
        div.ondragstart = function(e) {
            dragSrcIndex = i;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', i);
            this.classList.add('opacity-50');
        };
        div.ondragend = function() {
            this.classList.remove('opacity-50');
        };
        div.ondragover = function(e) {
            e.preventDefault();
            this.classList.add('ring-2', 'ring-pink-400');
        };
        div.ondragleave = function() {
            this.classList.remove('ring-2', 'ring-pink-400');
        };
        div.ondrop = function(e) {
            e.preventDefault();
            this.classList.remove('ring-2', 'ring-pink-400');
            const targetIndex = parseInt(this.getAttribute('data-index'));
            if (dragSrcIndex !== null && dragSrcIndex !== targetIndex) {
                const moved = campos.splice(dragSrcIndex, 1)[0];
                campos.splice(targetIndex, 0, moved);
                renderCampos();
            }
            dragSrcIndex = null;
        };
        div.innerHTML = `
            <div class='flex-1 flex flex-col md:flex-row gap-2 items-center'>
                <input type="text" placeholder="Pergunta" value="${campo.label}" class="px-3 py-2 border border-pink-200 rounded-lg w-full md:w-1/2 focus:ring-2 focus:ring-pink-300 text-sm font-semibold" onchange="campos[${i}].label=this.value;renderCampos()" required />
                <select class="px-3 py-2 border border-orange-200 rounded-lg focus:ring-2 focus:ring-orange-300 text-sm" onchange="campos[${i}].type=this.value;renderCampos()">
                    <option value="text" ${campo.type==='text'?'selected':''}>Texto</option>
                    <option value="textarea" ${campo.type==='textarea'?'selected':''}>Área de Texto</option>
                    <option value="number" ${campo.type==='number'?'selected':''}>Número</option>
                    <option value="radio" ${campo.type==='radio'?'selected':''}>Escolha Única</option>
                    <option value="checkbox" ${campo.type==='checkbox'?'selected':''}>Múltipla Escolha</option>
                </select>
                <input type="text" placeholder="Opções (separadas por vírgula)" value="${campo.options||''}" class="px-3 py-2 border border-orange-100 rounded-lg w-full md:w-1/2 focus:ring-2 focus:ring-orange-200 text-sm ${['radio','checkbox','select'].includes(campo.type)?'':'hidden'}" onchange="campos[${i}].options=this.value;" ${['radio','checkbox','select'].includes(campo.type)?'':'disabled'} />
            </div>
            <div class='flex gap-2 mt-2 md:mt-0 md:ml-2'>
                <button type="button" class="px-2 py-1 bg-red-500 text-white rounded shadow hover:bg-red-600 transition text-xs" onclick="campos.splice(${i},1);renderCampos()"><svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M6 18L18 6M6 6l12 12' /></svg></button>
            </div>
            <span class='absolute left-0 top-0 ml-2 mt-2 text-pink-300 text-xs select-none hidden md:block'><svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4 inline' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4 8h16M4 16h16' /></svg> Arraste para reordenar</span>
        `;
        lista.appendChild(div);
    });
}
document.getElementById('add-campo').onclick = function() {
    campos.push({label:'',type:'text',options:''});
    renderCampos();
};
document.getElementById('form-estrutura').onsubmit = function(e) {
    if(campos.length===0){
        alert('Adicione pelo menos um campo!');
        e.preventDefault();
        return false;
    }
    // Limpa opções para tipos que não usam
    campos = campos.map(c => {
        if(['radio','checkbox','select'].includes(c.type)){
            return {...c, options: c.options||''};
        } else {
            const {options, ...rest} = c;
            return rest;
        }
    });
    document.getElementById('estrutura-json').value = JSON.stringify(campos);
};
renderCampos();
</script>
@endsection
