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
            <!-- Botão de videochamada -->
            <button id="start-video-call" title="Iniciar videochamada" class="ml-auto p-2 rounded-full bg-gradient-to-r from-pink-500 to-orange-400 text-white shadow hover:scale-110 transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 7h6a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z" />
                </svg>
            </button>
        </div>
        <!-- Área de vídeo chamada -->
        <div id="video-call-area" class="flex flex-col items-center justify-center mb-4">
            <div class="w-full flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-2">
                <div class="flex flex-col md:flex-row gap-2 md:gap-4 w-full md:w-auto justify-center items-center">
                    <label class="text-xs text-pink-500 font-semibold flex flex-col items-start">
                        Câmera
                        <select id="videoSource" class="mt-1 px-2 py-1 rounded border border-pink-200 text-sm bg-white focus:ring-2 focus:ring-pink-400 transition w-40"></select>
                    </label>
                    <label class="text-xs text-orange-500 font-semibold flex flex-col items-start">
                        Microfone
                        <select id="audioSource" class="mt-1 px-2 py-1 rounded border border-orange-200 text-sm bg-white focus:ring-2 focus:ring-orange-400 transition w-40"></select>
                    </label>
                </div>
                <div class="flex gap-2 justify-center md:justify-end mt-2 md:mt-0">
                    <button id="toggle-mic" class="px-3 py-1 rounded-lg bg-pink-400 text-white font-bold shadow hover:bg-pink-500 transition text-xs md:text-sm">Mutar</button>
                    <button id="toggle-cam" class="px-3 py-1 rounded-lg bg-orange-400 text-white font-bold shadow hover:bg-orange-500 transition text-xs md:text-sm">Desligar Câmera</button>
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-4 w-full justify-center items-center">
                <video id="localVideo" autoplay playsinline muted class="rounded-xl border-2 border-pink-300 w-full md:w-48 h-40 md:h-32 bg-black shadow-md"></video>
                <video id="remoteVideo" autoplay playsinline class="rounded-xl border-2 border-orange-300 w-full md:w-48 h-40 md:h-32 bg-black shadow-md"></video>
            </div>
            <button id="end-video-call" class="mt-4 px-6 py-2 bg-red-500 text-white rounded-xl font-bold shadow hover:bg-red-600 transition text-sm md:text-base">Encerrar chamada</button>
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

<!-- Modal de chamada recebida -->
<div id="incoming-call-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-2xl shadow-2xl p-8 border-2 border-pink-200 flex flex-col items-center gap-4 max-w-xs w-full">
        <div class="text-pink-500 text-2xl font-bold">Chamada recebida</div>
        <div class="text-gray-700 text-lg font-semibold" id="caller-name"></div>
        <div class="flex gap-4 mt-2">
            <button id="accept-call" class="px-4 py-2 bg-green-500 text-white rounded-lg font-bold shadow hover:bg-green-600 transition">Atender</button>
            <button id="decline-call" class="px-4 py-2 bg-red-500 text-white rounded-lg font-bold shadow hover:bg-red-600 transition">Recusar</button>
        </div>
    </div>
    <audio id="ringtone" src="https://cdn.pixabay.com/audio/2022/07/26/audio_124bfa4c3b.mp3" loop></audio>
</div>

<script>
    const userId = {{ auth()->id() }};
    const userName = @json(auth()->user()->name);
    const toId = {{ $otherUser->id }};
    const toName = @json($otherUser->name);
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const incomingCallModal = document.getElementById('incoming-call-modal');
    const callerNameEl = document.getElementById('caller-name');
    const ringtone = document.getElementById('ringtone');
    let isCaller = false;
    let callTimeout = null;

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

        // --- VIDEOCHAMADA ---
        const videoCallArea = document.getElementById('video-call-area');
        const localVideo = document.getElementById('localVideo');
        const remoteVideo = document.getElementById('remoteVideo');
        let localStream;
        let peerConnection;
        let currentVideoDeviceId = null;
        let currentAudioDeviceId = null;
        let micMuted = false;
        let camOff = false;
        const servers = {
            iceServers: [
                { urls: 'stun:stun.l.google.com:19302' },
                { urls: 'stun:stun1.l.google.com:19302' },
                { urls: 'stun:stun2.l.google.com:19302' },
                { urls: 'stun:stun3.l.google.com:19302' }
            ]
        };

        // Sinalização via Echo (whisper, canal privado)
        function sendSignal(type, payload) {
            window.Echo.private('chat.' + toId + '.' + userId)
                .whisper('video-signal', { type, payload, from_id: userId, to_id: toId });
        }

        // Receber sinalização
        window.Echo.private('chat.' + userId + '.' + toId)
            .listenForWhisper('video-signal', async (e) => {
                if (e.type === 'call-request') {
                    // Exibe modal de chamada recebida
                    callerNameEl.textContent = e.payload.callerName || 'Usuário';
                    incomingCallModal.classList.remove('hidden');
                    incomingCallModal.classList.add('flex');
                    try { ringtone.currentTime = 0; ringtone.play(); } catch (err) {}
                } else if (e.type === 'call-accept') {
                    // Iniciar chamada como quem ligou
                    isCaller = true;
                    incomingCallModal.classList.add('hidden');
                    incomingCallModal.classList.remove('flex');
                    ringtone.pause(); ringtone.currentTime = 0;
                    startCall();
                } else if (e.type === 'call-decline') {
                    // Chamada recusada
                    isCaller = false;
                    incomingCallModal.classList.add('hidden');
                    incomingCallModal.classList.remove('flex');
                    ringtone.pause(); ringtone.currentTime = 0;
                    alert('Chamada recusada');
                } else if (e.type === 'video-offer') {
                    if (!isCaller) await handleOffer(e.payload);
                } else if (e.type === 'video-answer') {
                    if (isCaller) await handleAnswer(e.payload);
                } else if (e.type === 'ice-candidate') {
                    if (peerConnection) await peerConnection.addIceCandidate(new RTCIceCandidate(e.payload));
                } else if (e.type === 'end-call') {
                    endCallUI();
                }
            });

        async function getDevices() {
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoSelect = document.getElementById('videoSource');
            const audioSelect = document.getElementById('audioSource');
            videoSelect.innerHTML = '';
            audioSelect.innerHTML = '';
            devices.forEach(device => {
                if (device.kind === 'videoinput') {
                    const option = document.createElement('option');
                    option.value = device.deviceId;
                    option.text = device.label || `Câmera ${videoSelect.length + 1}`;
                    videoSelect.appendChild(option);
                } else if (device.kind === 'audioinput') {
                    const option = document.createElement('option');
                    option.value = device.deviceId;
                    option.text = device.label || `Microfone ${audioSelect.length + 1}`;
                    audioSelect.appendChild(option);
                }
            });
        }

        async function getMedia(videoId, audioId) {
            const constraints = {
                video: videoId ? { deviceId: { exact: videoId } } : true,
                audio: audioId ? { deviceId: { exact: audioId } } : true
            };
            return await navigator.mediaDevices.getUserMedia(constraints);
        }

        async function switchDevice(type, deviceId) {
            if (!localStream) return;
            let newStream = await getMedia(
                type === 'video' ? deviceId : currentVideoDeviceId,
                type === 'audio' ? deviceId : currentAudioDeviceId
            );
            // Substitui as tracks no localStream
            newStream.getTracks().forEach(track => {
                const oldTrack = localStream.getTracks().find(t => t.kind === track.kind);
                if (oldTrack) {
                    localStream.removeTrack(oldTrack);
                    oldTrack.stop();
                }
                localStream.addTrack(track);
                if (peerConnection) {
                    const sender = peerConnection.getSenders().find(s => s.track && s.track.kind === track.kind);
                    if (sender) sender.replaceTrack(track);
                }
            });
            if (type === 'video') {
                currentVideoDeviceId = deviceId;
                document.getElementById('localVideo').srcObject = localStream;
            }
            if (type === 'audio') {
                currentAudioDeviceId = deviceId;
            }
        }

        // Preenche selects ao abrir a página
        getDevices();
        navigator.mediaDevices.ondevicechange = getDevices;

        document.getElementById('videoSource').addEventListener('change', function() {
            switchDevice('video', this.value);
        });
        document.getElementById('audioSource').addEventListener('change', function() {
            switchDevice('audio', this.value);
        });

        document.getElementById('toggle-mic').addEventListener('click', function() {
            if (!localStream) return;
            micMuted = !micMuted;
            localStream.getAudioTracks().forEach(track => track.enabled = !micMuted);
            this.textContent = micMuted ? 'Desmutar' : 'Mutar';
        });
        document.getElementById('toggle-cam').addEventListener('click', function() {
            if (!localStream) return;
            camOff = !camOff;
            localStream.getVideoTracks().forEach(track => track.enabled = !camOff);
            this.textContent = camOff ? 'Ligar Câmera' : 'Desligar Câmera';
        });

        // Botão de iniciar chamada
        // Substitui o evento antigo
        const startCallBtn = document.getElementById('start-video-call');
        startCallBtn.addEventListener('click', () => {
            isCaller = true;
            sendSignal('call-request', { callerName: userName });
            // (Opcional) Exibir feedback de "Chamando..." para o usuário A
        });

        // Aceitar chamada
        const acceptCallBtn = document.getElementById('accept-call');
        acceptCallBtn.addEventListener('click', () => {
            incomingCallModal.classList.add('hidden');
            incomingCallModal.classList.remove('flex');
            ringtone.pause(); ringtone.currentTime = 0;
            sendSignal('call-accept');
            isCaller = false;
            startCall();
        });

        // Recusar chamada
        const declineCallBtn = document.getElementById('decline-call');
        declineCallBtn.addEventListener('click', () => {
            incomingCallModal.classList.add('hidden');
            incomingCallModal.classList.remove('flex');
            ringtone.pause(); ringtone.currentTime = 0;
            sendSignal('call-decline');
            isCaller = false;
        });

        // Função para iniciar a chamada (só após aceitar)
        async function startCall() {
            videoCallArea.classList.remove('hidden');
            await getDevices();
            const videoSelect = document.getElementById('videoSource');
            const audioSelect = document.getElementById('audioSource');
            currentVideoDeviceId = videoSelect.value;
            currentAudioDeviceId = audioSelect.value;
            localStream = await getMedia(currentVideoDeviceId, currentAudioDeviceId);
            localVideo.srcObject = localStream;
            peerConnection = new RTCPeerConnection(servers);
            localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));
            peerConnection.onicecandidate = (event) => {
                if (event.candidate) sendSignal('ice-candidate', event.candidate);
            };
            peerConnection.ontrack = (event) => {
                remoteVideo.srcObject = event.streams[0];
            };
            if (isCaller) {
                const offer = await peerConnection.createOffer();
                await peerConnection.setLocalDescription(offer);
                sendSignal('video-offer', offer);
            }
        }

        document.getElementById('end-video-call').addEventListener('click', () => {
            sendSignal('end-call');
            endCallUI();
        });

        async function handleOffer(offer) {
            videoCallArea.classList.remove('hidden');
            await getDevices();
            const videoSelect = document.getElementById('videoSource');
            const audioSelect = document.getElementById('audioSource');
            currentVideoDeviceId = videoSelect.value;
            currentAudioDeviceId = audioSelect.value;
            localStream = await getMedia(currentVideoDeviceId, currentAudioDeviceId);
            localVideo.srcObject = localStream;
            peerConnection = new RTCPeerConnection(servers);
            localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));
            peerConnection.onicecandidate = (event) => {
                if (event.candidate) sendSignal('ice-candidate', event.candidate);
            };
            peerConnection.ontrack = (event) => {
                remoteVideo.srcObject = event.streams[0];
            };
            await peerConnection.setRemoteDescription(new RTCSessionDescription(offer));
            const answer = await peerConnection.createAnswer();
            await peerConnection.setLocalDescription(answer);
            sendSignal('video-answer', answer);
        }

        async function handleAnswer(answer) {
            if (peerConnection) {
                await peerConnection.setRemoteDescription(new RTCSessionDescription(answer));
            }
        }

        function endCallUI() {
            videoCallArea.classList.add('hidden');
            if (peerConnection) {
                peerConnection.close();
                peerConnection = null;
            }
            if (localStream) {
                localStream.getTracks().forEach(track => track.stop());
            }
            localVideo.srcObject = null;
            remoteVideo.srcObject = null;
            micMuted = false;
            camOff = false;
            document.getElementById('toggle-mic').textContent = 'Mutar';
            document.getElementById('toggle-cam').textContent = 'Desligar Câmera';
            incomingCallModal.classList.add('hidden');
            incomingCallModal.classList.remove('flex');
            ringtone.pause(); ringtone.currentTime = 0;
            isCaller = false;
        }
    });
</script>
@endsection
