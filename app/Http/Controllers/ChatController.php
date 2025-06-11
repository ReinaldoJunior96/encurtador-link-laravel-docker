<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use App\Events\ChatMessageSent;
use App\Events\UserTyping;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'user' => 'nullable|string|max:100',
        ]);
        $user = $request->user ?? 'Visitante';
        broadcast(new ChatMessageSent($request->message, $user));
        return response()->json(['status' => 'ok']);
    }

    public function typing(Request $request)
    {
        $user = $request->user;
        broadcast(new UserTyping($user));
        return response()->json(['status' => 'ok']);
    }
}
