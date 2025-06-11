<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;

// Este arquivo é necessário para o sistema de broadcast do Laravel funcionar.
// Para canais públicos, não é preciso registrar nada aqui.
Broadcast::channel('ChatMessageSent', function () {
    //
});

Broadcast::channel('chat.{from}.{to}', function ($user, $from, $to) {
    // Permite que apenas os dois usuários envolvidos acessem o canal
    return (int)$user->id === (int)$from || (int)$user->id === (int)$to;
});
