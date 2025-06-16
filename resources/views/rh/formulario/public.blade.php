@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-orange-50 to-pink-100 flex flex-col items-center py-10">
    <div class="w-full max-w-2xl bg-white/90 rounded-2xl shadow-2xl p-8 border border-pink-100 backdrop-blur-md">
        <h1 class="text-2xl font-bold text-pink-600 mb-6">{{ $formulario->titulo }}</h1>
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-center font-semibold">{{ session('success') }}</div>
        @endif
        <form method="POST" action="{{ route('formulario.responder', $formulario->hashSlug) }}">
            @csrf
            @foreach((array)json_decode($formulario->estrutura, true) as $i => $campo)
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-pink-500 mb-1">{{ $campo['label'] ?? 'Campo' }}</label>
                    @if($campo['type'] === 'text')
                        <input type="text" name="respostas[{{ $i }}]" class="w-full px-4 py-2 rounded-lg border border-pink-200 focus:ring-2 focus:ring-pink-400 bg-white text-gray-800" required />
                    @elseif($campo['type'] === 'textarea')
                        <textarea name="respostas[{{ $i }}]" class="w-full px-4 py-2 rounded-lg border border-pink-200 focus:ring-2 focus:ring-pink-400 bg-white text-gray-800" required></textarea>
                    @elseif($campo['type'] === 'number')
                        <input type="number" name="respostas[{{ $i }}]" class="w-full px-4 py-2 rounded-lg border border-pink-200 focus:ring-2 focus:ring-pink-400 bg-white text-gray-800" required />
                    @elseif($campo['type'] === 'radio' && !empty($campo['options']))
                        <div class="flex flex-wrap gap-4">
                            @foreach(explode(',', $campo['options']) as $op)
                                <label><input type="radio" name="respostas[{{ $i }}]" value="{{ trim($op) }}" required> {{ trim($op) }}</label>
                            @endforeach
                        </div>
                    @elseif($campo['type'] === 'checkbox' && !empty($campo['options']))
                        <div class="flex flex-wrap gap-4">
                            @foreach(explode(',', $campo['options']) as $op)
                                <label><input type="checkbox" name="respostas[{{ $i }}][]" value="{{ trim($op) }}"> {{ trim($op) }}</label>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
            <div class="flex gap-4 mt-6">
                <button type="submit" class="px-6 py-2 bg-pink-500 text-white rounded-lg font-bold shadow hover:bg-pink-600 transition">Enviar Resposta</button>
            </div>
        </form>
    </div>
</div>
@endsection
