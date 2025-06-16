@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-orange-50 to-pink-100 flex flex-col items-center py-10">
    <div class="w-full max-w-4xl bg-white/90 rounded-2xl shadow-2xl p-8 border border-pink-100 backdrop-blur-md">
        <h1 class="text-3xl font-bold text-pink-600 mb-6 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6 5.87V4m0 0L5 9m7-5l7 5" /></svg>
            Gestão de Pessoas (RH)
        </h1>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-xl shadow border border-pink-100">
                <thead>
                    <tr class="bg-pink-50 text-pink-600">
                        <th class="px-4 py-2 text-left">Nome</th>
                        <th class="px-4 py-2 text-left">E-mail</th>
                        <th class="px-4 py-2 text-left">Cargo</th>
                        <th class="px-4 py-2 text-left">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-b border-pink-100 hover:bg-orange-50 transition">
                        <td class="px-4 py-2 font-semibold text-gray-800">{{ $user->name }}</td>
                        <td class="px-4 py-2 text-gray-500">{{ $user->email }}</td>
                        <td class="px-4 py-2 text-orange-500 font-bold uppercase">{{ $user->role }}</td>
                        <td class="px-4 py-2 flex gap-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="px-3 py-1 bg-pink-400 text-white rounded-lg font-bold shadow hover:bg-pink-500 transition text-xs">Editar</a>
                            <a href="{{ route('chat.private', $user->id) }}" class="px-3 py-1 bg-orange-400 text-white rounded-lg font-bold shadow hover:bg-orange-500 transition text-xs">Chat</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
