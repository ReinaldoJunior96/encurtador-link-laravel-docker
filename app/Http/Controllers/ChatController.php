<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use App\Events\ChatMessageSent;
use App\Events\UserTyping;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Chat público: salva mensagem no banco
        $request->validate([
            'message' => 'required|string',
            'user' => 'required|string',
        ]);
        $user = Auth::user();
        // Salva mensagem no banco, associando ao usuário logado
        $msg = Message::create([
            'from_id' => $user->id,
            'to_id' => null, // público
            'message' => $request->message,
        ]);
        broadcast(new \App\Events\ChatMessageSent($request->message, $user, null))->toOthers();
        return response()->json(['status' => 'ok']);
    }

    public function typing(Request $request)
    {
        $user = $request->user;
        broadcast(new UserTyping($user));
        return response()->json(['status' => 'ok']);
    }

    public function privateChat(User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users');
        }
        // Buscar histórico de mensagens entre os dois usuários
        $messages = Message::where(function ($q) use ($user) {
            $q->where('from_id', Auth::id())->where('to_id', $user->id);
        })->orWhere(function ($q) use ($user) {
            $q->where('from_id', $user->id)->where('to_id', Auth::id());
        })->orderBy('created_at')->get();
        return view('private-chat', [
            'otherUser' => $user,
            'messages' => $messages,
        ]);
    }

    public function sendPrivateMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'to_id' => 'required|exists:users,id',
        ]);
        $user = Auth::user();
        $to_id = $request->to_id;
        // Salvar mensagem no banco
        $msg = Message::create([
            'from_id' => $user->id,
            'to_id' => $to_id,
            'message' => $request->message,
        ]);
        broadcast(new \App\Events\ChatMessageSent($request->message, $user, $to_id))->toOthers();
        return response()->json(['status' => 'ok']);
    }

    public function publicChat()
    {
        $messages = Message::whereNull('to_id')->orderBy('created_at')->get();
        return view('chat', compact('messages'));
    }
}
