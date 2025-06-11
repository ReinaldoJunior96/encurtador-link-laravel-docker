import './bootstrap';

window.Echo.channel('public.chat')
    .listen('ChatMessageSent', (e) => {
        console.log("reinaldo")
        addMessage(e.message, e.user || 'Visitante', false);
    });

function addMessage(text, user, isSelf) {
    const messages = document.getElementById('chat-messages');
    if (!messages) return;
    const div = document.createElement('div');
    div.className = isSelf ? 'flex justify-end' : 'flex justify-start';
    div.innerHTML = `<div class='${isSelf ? 'bg-gradient-to-r from-pink-500 to-orange-400 text-white' : 'bg-pink-100 text-gray-800'} px-5 py-3 rounded-xl max-w-xs shadow-lg mb-2'>
        <span class='block text-xs font-bold mb-1'>${user}</span>${text}
    </div>`;
    messages.appendChild(div);
    messages.scrollTop = messages.scrollHeight;
}