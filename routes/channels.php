<?php

use Illuminate\Support\Facades\Broadcast;
// Este arquivo é necessário para o sistema de broadcast do Laravel funcionar.
// Para canais públicos, não é preciso registrar nada aqui.
Broadcast::channel('ChatMessageSent', function () {
    //
});
