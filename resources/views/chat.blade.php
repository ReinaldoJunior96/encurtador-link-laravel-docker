@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-pink-50 via-orange-50 to-pink-100 flex flex-col">
  <!-- Header -->
  <header
    class="w-full px-6 py-4 flex justify-between items-center bg-white/80 backdrop-blur-sm border-b border-pink-100">
    <div class="flex items-center gap-2">
      <div class="w-8 h-8 bg-gradient-to-r from-pink-500 to-orange-400 rounded transform rotate-12"></div>
      <span class="text-2xl font-bold text-gray-800">LinkShrink</span>
    </div>
    <a href="/"
      class="flex items-center gap-2 bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 transition-colors">
      <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-home" width="18" height="18" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path d="M3 12l9-9 9 9M4 10v10a1 1 0 0 0 1 1h5m4 0h5a1 1 0 0 0 1-1V10" />
      </svg>
      <span>Home</span>
    </a>
  </header>

  <!-- Chat Section -->
  <main class="flex-1 flex flex-col items-center justify-center px-4 py-10 ">
    <div
      class="w-full max-w-2xl mx-auto flex flex-col bg-white/90 rounded-3xl shadow-2xl border border-pink-100 overflow-hidden">
      <div class="px-8 py-6 border-b border-pink-100 bg-gradient-to-r from-pink-100 to-orange-100">
        <h2 class="text-2xl font-bold text-gray-800">Chat</h2>
        <p class="text-pink-600 text-sm">Converse em tempo real com nosso suporte ou tire dúvidas!</p>
      </div>
      <div id="chat-messages"
        class="flex-1 px-8 py-6 space-y-4 overflow-y-auto h-[450px] md:h-[600px] bg-gradient-to-br from-pink-50 to-orange-50">
        @foreach($messages as $msg)
          <div class="{{ $msg->from_id === auth()->id() ? 'flex justify-end' : 'flex justify-start' }}">
            <div class="{{ $msg->from_id === auth()->id() ? 'bg-gradient-to-r from-pink-500 to-orange-400 text-white' : 'bg-pink-100 text-gray-800' }} px-5 py-3 rounded-xl max-w-xs max-w-[320px] md:max-w-[400px] shadow-lg mb-2 break-words overflow-auto">
              <span class="block text-xs font-bold mb-1">{{ $msg->from_id === auth()->id() ? 'Você' : ($msg->from->name ?? 'Usuário') }}</span>
              {{ $msg->message }}
            </div>
          </div>
        @endforeach
      </div>
      <div id="chat-typing" class="px-8 py-2 text-sm text-pink-500 font-semibold" style="display:none;"></div>
      <div class="flex items-center gap-3 px-8 py-6 bg-white border-t border-pink-100">
        <input type="hidden" id="chat-username" value="{{ auth()->user()->name }}" />
        <input type="text" id="chat-input" placeholder="Digite sua mensagem..." class="flex-1 px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-pink-500 focus:border-transparent outline-none text-gray-800 bg-pink-50" required />
        <button id="chat-send-btn" class="bg-gradient-to-r from-pink-500 to-orange-400 text-white font-semibold px-6 py-3 rounded-lg hover:from-pink-600 hover:to-orange-500 transition-all duration-300 shadow-lg flex items-center gap-2">
          <span>Enviar</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="lucide lucide-send" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M22 2L11 13"/><path d="M22 2L15 22L11 13L2 9L22 2Z"/>
          </svg>
        </button>
      </div>
    </div>
  </main>
</div>
<script>
  const input = document.getElementById('chat-input');
  const sendBtn = document.getElementById('chat-send-btn');
  const messages = document.getElementById('chat-messages');
  const username = document.getElementById('chat-username').value;

  function addMessage(text, user, isSelf) {
    // Evita adicionar objetos ou duplicar nomes
    if (typeof text === 'object') {
      text = JSON.stringify(text);
    }
    if (typeof user === 'object') {
      user = user?.name || 'Usuário';
    }
    const div = document.createElement('div');
    div.className = isSelf ? 'flex justify-end' : 'flex justify-start';
    div.innerHTML = `<div class='${isSelf ? 'bg-gradient-to-r from-pink-500 to-orange-400 text-white' : 'bg-pink-100 text-gray-800'} px-5 py-3 rounded-xl max-w-xs max-w-[320px] md:max-w-[400px] shadow-lg mb-2'>
            <span class='block text-xs font-bold mb-1'>${user}</span>${text}
        </div>`;
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
  }

  function sendMessage(e) {
    if (e) e.preventDefault();
    if (input.value.trim() === '' || username.trim() === '') return;
    fetch('/chat/message', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
      body: JSON.stringify({ message: input.value, user: username }),
    })
    .then(res => {
      if (!res.ok) throw new Error('Erro ao enviar mensagem');
      return res.json();
    })
    .then(data => {
      addMessage(input.value, username, true);
      input.value = '';
    })
    .catch(err => {
      alert('Erro ao enviar mensagem!');
    });
  }

  sendBtn.addEventListener('click', sendMessage);
  input.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      sendMessage(e);
    }
  });

  let typingTimeout;
  let isTyping = false;

  input.addEventListener('input', function() {
    if (!isTyping) {
      isTyping = true;
      fetch('/chat/typing', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ user: username })
      });
    }
    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {
      isTyping = false;
      fetch('/chat/typing', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: JSON.stringify({ user: null })
      });
    }, 1500);
  });

  document.addEventListener('DOMContentLoaded', function() {
    function waitForEchoAndInit() {
      if (window.Echo && typeof window.Echo.channel === 'function') {
        // Força o subscribe ao canal correto e mostra no console
        const channel = window.Echo.channel('public.chat');
        console.log('Subscribing to public.chat', channel);
        channel.listen('ChatMessageSent', (e) => {
          console.log('Recebido ChatMessageSent:', e);
          // Evita duplicar mensagem se já existe no DOM
          const lastMsg = messages.lastElementChild;
          if (!lastMsg || lastMsg.textContent.trim() !== e.message.trim() || (e.user?.name !== username)) {
            addMessage(e.message, e.user?.name || 'Visitante', e.user?.name === username);
          }
        })
        .listen('UserTyping', (e) => {
          const typingDiv = document.getElementById('chat-typing');
          if (e.user && e.user !== username) {
            typingDiv.textContent = `${e.user} está digitando...`;
            typingDiv.style.display = 'block';
          } else {
            typingDiv.textContent = '';
            typingDiv.style.display = 'none';
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