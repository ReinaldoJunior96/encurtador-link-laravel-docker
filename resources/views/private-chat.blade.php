@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-orange-50 to-pink-100 flex flex-col items-center py-10">
    <div class="w-full max-w-2xl bg-white/90 rounded-2xl shadow-2xl p-8 border border-pink-100 backdrop-blur-md">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full bg-gradient-to-r from-pink-400 to-orange-400 flex items-center justify-center text-white font-bold text-2xl">
                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
            </div>
            <div>
                <div class="font-bold text-lg text-gray-800">{{ $otherUser->name }}</div>
                <div class="text-xs text-gray-400">{{ $otherUser->email }}</div>
            </div>
        </div>
        <div id="chat-messages" class="h-64 overflow-y-auto bg-pink-50 rounded-lg p-4 mb-4 border border-pink-100">
            @foreach($messages as $msg)
                <div class="mb-2 flex {{ $msg->from_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <span class="inline-block px-4 py-2 rounded-lg {{ $msg->from_id === auth()->id() ? 'bg-pink-500 text-white' : 'bg-orange-100 text-gray-800' }} max-w-xs">
                        <span class="block text-xs font-bold mb-1">{{ $msg->from_id === auth()->id() ? 'Você' : $otherUser->name }}</span>
                        {{ $msg->message }}
                    </span>
                </div>
            @endforeach
        </div>
        <form id="chat-form" class="flex gap-2">
            <input type="hidden" id="to_id" value="{{ $otherUser->id }}">
            <input type="text" id="chat-input" class="flex-1 px-4 py-2 rounded-lg border border-pink-200 focus:ring-2 focus:ring-pink-400 bg-white text-gray-800 placeholder:text-pink-400" placeholder="Digite sua mensagem..." autocomplete="off">
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-pink-500 to-orange-400 text-white rounded-lg font-bold shadow hover:scale-105 transition">Enviar</button>
        </form>
    </div>
</div>
<script>
    const userId = {{ auth()->id() }};
    const userName = @json(auth()->user()->name);
    const toId = {{ $otherUser->id }};
    const toName = @json($otherUser->name);
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');

    function addMessage(message, sender, isOwn) {
        const div = document.createElement('div');
        div.className = 'mb-2 flex ' + (isOwn ? 'justify-end' : 'justify-start');
        div.innerHTML = `<span class="inline-block px-4 py-2 rounded-lg ${isOwn ? 'bg-pink-500 text-white' : 'bg-orange-100 text-gray-800'} max-w-xs">` +
            `<span class="block text-xs font-bold mb-1">${sender}</span>` +
            message + '</span>';
        chatMessages.appendChild(div);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message) return;
        addMessage(message, userName, true);
        fetch("{{ route('chat.private.message') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message, to_id: toId })
        });
        chatInput.value = '';
    });

    document.addEventListener('DOMContentLoaded', function() {
        function waitForEchoAndInit() {
            if (window.Echo && typeof window.Echo.private === 'function') {
                window.Echo.private('chat.' + userId + '.' + toId)
                    .listen('ChatMessageSent', (e) => {
                        if (e.user && e.user.id !== userId) {
                            addMessage(e.message, e.user.name, false);
                        }
                    });
                window.Echo.private('chat.' + toId + '.' + userId)
                    .listen('ChatMessageSent', (e) => {
                        if (e.user && e.user.id !== userId) {
                            addMessage(e.message, e.user.name, false);
                        }
                    });
            } else {
                setTimeout(waitForEchoAndInit, 100);
            }
        }
        waitForEchoAndInit();
    });
</script>
@endsection
