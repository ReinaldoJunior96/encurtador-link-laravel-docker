<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user;
    public $to_id;

    public function __construct($message, $user = null, $to_id)
    {
        $this->message = $message;
        $this->user = $user;
        $this->to_id = $to_id;
    }

    public function broadcastOn()
    {
        if (empty($this->to_id)) {
            // Chat público
            return new \Illuminate\Broadcasting\Channel('public.chat');
        }
        // Chat privado
        return new \Illuminate\Broadcasting\PrivateChannel('chat.' . $this->user->id . '.' . $this->to_id);
    }
}
