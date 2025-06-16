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
        <div class="w-full max-w-5xl mx-auto bg-white/90 rounded-2xl shadow-2xl p-8 border border-pink-100 backdrop-blur-md">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <h1 class="text-2xl font-bold text-pink-600 flex-1">Métricas do Formulário: <span class="text-orange-500">{{ $formulario->titulo }}</span></h1>
                <a href="{{ route('rh.formulario.index') }}" class="px-4 py-2 bg-pink-200 text-pink-700 rounded-lg font-bold shadow hover:bg-pink-300 transition">Voltar</a>
            </div>
            @if(!empty($erro_estrutura))
                <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 mb-8 rounded-xl text-center">
                    <strong>Estrutura do formulário não definida ou inválida.</strong><br>
                    Edite o formulário para adicionar campos e poder visualizar as métricas.
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    @foreach($resumo as $i => $campo)
                        <div class="bg-gradient-to-br from-pink-100 to-orange-100 rounded-2xl shadow p-6 border border-pink-100">
                            <h2 class="text-lg font-bold text-pink-600 mb-2">{{ $campo['label'] }}</h2>
                            <div class="mb-2 text-xs text-gray-500">Tipo: <span class="font-semibold text-orange-500">{{ $campo['type'] }}</span></div>
                            @if($campo['type'] === 'radio' || $campo['type'] === 'select' || $campo['type'] === 'checkbox')
                                @if(count($campo['contagem']))
                                    <div class="w-full h-48">
                                        <canvas id="grafico-{{ $i }}"></canvas>
                                    </div>
                                @else
                                    <div class="text-gray-400 italic">Sem respostas ainda</div>
                                @endif
                            @elseif($campo['type'] === 'number')
                                @if(count($campo['valores']))
                                    <div class="w-full h-48">
                                        <canvas id="grafico-{{ $i }}"></canvas>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500 flex flex-wrap gap-4">
                                        <span>Média: <span class="font-bold text-pink-600">{{ number_format(array_sum($campo['valores'])/count($campo['valores']), 2, ',', '.') }}</span></span>
                                        <span>Mín: <span class="font-bold text-orange-500">{{ number_format(min($campo['valores']), 2, ',', '.') }}</span></span>
                                        <span>Máx: <span class="font-bold text-orange-500">{{ number_format(max($campo['valores']), 2, ',', '.') }}</span></span>
                                    </div>
                                @else
                                    <div class="text-gray-400 italic">Sem respostas ainda</div>
                                @endif
                            @elseif(in_array($campo['type'], ['text','textarea']))
                                <div class="flex flex-col items-center justify-center h-32">
                                    <span class="text-4xl font-extrabold text-orange-500">{{ count($campo['valores']) }}</span>
                                    <span class="text-xs text-gray-500 mt-1">respostas recebidas</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="bg-white rounded-2xl shadow p-6 border border-pink-100 mb-8">
                    <h2 class="text-xl font-bold text-orange-500 mb-4">Respostas Detalhadas</h2>
                    <div class="mb-4 flex flex-col md:flex-row gap-2 items-center justify-between">
                        <input type="text" id="buscaTabela" placeholder="Buscar nas respostas..." class="w-full md:w-1/3 px-4 py-2 border border-pink-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-300 text-sm" />
                    </div>
                    <div class="overflow-x-auto w-full">
                        <div class="max-h-96 overflow-y-auto">
                            <table class="min-w-full divide-y divide-pink-100 text-sm" id="tabelaRespostas">
                                <thead class="sticky top-0 bg-pink-50 z-10">
                                    <tr class="text-pink-600">
                                        @foreach($estrutura as $campo)
                                            <th class="px-4 py-2 text-left whitespace-nowrap">{{ $campo['label'] ?? 'Campo' }}</th>
                                        @endforeach
                                        <th class="px-4 py-2 text-left whitespace-nowrap">Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($respostas as $resp)
                                    <tr class="border-b border-pink-100 hover:bg-orange-50 transition">
                                        @php $dados = is_array($resp->respostas) ? $resp->respostas : json_decode($resp->respostas, true); @endphp
                                        @foreach($estrutura as $i => $campo)
                                            <td class="px-4 py-2 whitespace-nowrap">
                                                @if($campo['type'] === 'checkbox')
                                                    @if(isset($dados[$i]) && is_array($dados[$i]))
                                                        {{ implode(', ', $dados[$i]) }}
                                                    @else
                                                        -
                                                    @endif
                                                @else
                                                    {{ $dados[$i] ?? '-' }}
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="px-4 py-2 text-xs text-gray-500 whitespace-nowrap">{{ $resp->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="{{ count($estrutura)+1 }}" class="text-center text-gray-400 py-8">Nenhuma resposta recebida ainda.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row gap-4 justify-end">
                    <form method="GET" action="#" onsubmit="exportCSV(event)" class="inline-block">
                        <button type="submit" class="px-6 py-2 bg-orange-400 text-white rounded-lg font-bold shadow hover:bg-orange-500 transition">Exportar CSV</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@foreach($resumo as $i => $campo)
@if(($campo['type'] === 'radio' || $campo['type'] === 'select' || $campo['type'] === 'checkbox') && count($campo['contagem']))
    const ctx{{ $i }} = document.getElementById('grafico-{{ $i }}').getContext('2d');
    new Chart(ctx{{ $i }}, {
        type: '{{ in_array($campo['type'], ['radio','checkbox']) ? 'pie' : 'bar' }}',
        data: {
            labels: {!! json_encode(array_keys($campo['contagem'])) !!},
            datasets: [{
                label: 'Respostas',
                data: {!! json_encode(array_values($campo['contagem'])) !!},
                backgroundColor: [
                    '#f472b6', '#fb7185', '#fbbf24', '#f59e42', '#f87171', '#fca5a5', '#fdba74', '#fcd34d', '#fbbf24', '#f472b6'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: { display: {{ in_array($campo['type'], ['radio','checkbox']) ? 'true' : 'false' }} }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
@endif
@if($campo['type'] === 'number' && count($campo['valores']))
    const ctxNum{{ $i }} = document.getElementById('grafico-{{ $i }}').getContext('2d');
    new Chart(ctxNum{{ $i }}, {
        type: 'bar',
        data: {
            labels: Array.from({length: {{ count($campo['valores']) }}}, (_,i) => i+1),
            datasets: [{
                label: 'Valores',
                data: {!! json_encode($campo['valores']) !!},
                backgroundColor: '#fb7185',
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            },
            responsive: true,
            maintainAspectRatio: false
        }
    });
@endif
@endforeach
function exportCSV(e) {
    e.preventDefault();
    let csv = '';
    const headers = [@foreach($estrutura as $campo)'{{ $campo['label'] ?? 'Campo' }}',@endforeach 'Data'];
    csv += headers.join(',') + '\n';
    @foreach($respostas as $resp)
        @php $dados = is_array($resp->respostas) ? $resp->respostas : json_decode($resp->respostas, true); @endphp
        var row = [
            @foreach($estrutura as $i => $campo)
                @if($campo['type'] === 'checkbox')
                    @if(isset($dados[$i]) && is_array($dados[$i]))
                        `{{ implode(' | ', $dados[$i]) }}`,
                    @else
                        '-',
                    @endif
                @else
                    `{{ $dados[$i] ?? '-' }}`,
                @endif
            @endforeach
            '{{ $resp->created_at->format('d/m/Y H:i') }}'
        ];
        csv += row.join(',') + '\n';
    @endforeach
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'respostas_formulario_{{ $formulario->id }}.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}
document.getElementById('buscaTabela').addEventListener('input', function() {
    const termo = this.value.toLowerCase();
    const linhas = document.querySelectorAll('#tabelaRespostas tbody tr');
    linhas.forEach(linha => {
        let textoLinha = linha.innerText.toLowerCase();
        linha.style.display = textoLinha.includes(termo) ? '' : 'none';
    });
});
</script>
@endsection
