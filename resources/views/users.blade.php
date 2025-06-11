@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-orange-50 to-pink-100 flex flex-col items-center py-10">
    <div class="w-full max-w-4xl bg-white/90 rounded-2xl shadow-2xl p-8 border border-pink-100 backdrop-blur-md">
        <h1 class="text-2xl font-extrabold text-gray-800 mb-6">Gerenciar Usuários</h1>
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-center font-semibold">{{ session('success') }}</div>
        @endif
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-pink-100">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-mail</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Permissão</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-pink-50">
                    @foreach($users as $user)
                    <tr class="hover:bg-pink-50">
                        <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-700">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-center">
                            <form method="POST" action="{{ route('users.updateRole', $user->id) }}" class="inline-block">
                                @csrf
                                <select name="role" class="px-3 py-2 rounded-lg border border-pink-200 focus:ring-2 focus:ring-pink-400 bg-pink-50 text-gray-800 font-semibold" onchange="this.form.submit()" {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                                    @foreach($roles as $value => $label)
                                        <option value="{{ $value }}" @if($user->role === $value) selected @endif>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(auth()->id() !== $user->id)
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $user->role === 'admin' ? 'bg-pink-500 text-white' : ($user->role === 'funcionario' ? 'bg-orange-400 text-white' : 'bg-pink-100 text-pink-700') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-500">Você</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
