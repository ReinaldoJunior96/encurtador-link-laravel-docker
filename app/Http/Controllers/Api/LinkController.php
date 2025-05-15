<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Link;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class LinkController extends Controller
{
    /**
     * Recebe uma URL e retorna um link encurtado
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function shorten(Request $request)
    {
        try {
            Log::info('Recebendo requisição para encurtar link', ['request' => $request->all()]);
            $validator = Validator::make($request->all(), [
                'url' => 'required|url',
            ]);
            if ($validator->fails()) {
                Log::warning('URL inválida recebida', ['errors' => $validator->errors()]);
                return response()->json(['error' => 'URL inválida'], 422);
            }

            $originalUrl = $request->input('url');
            // Gera um código curto único
            do {
                $shortCode = Str::random(6);
            } while (Link::where('short_code', $shortCode)->exists());

            // Define o tempo de expiração para 1 minuto a partir de agora
            $expiresAt = now()->addMinute();

            $link = Link::create([
                'original_url' => $originalUrl,
                'short_code' => $shortCode,
                'expires_at' => $expiresAt,
            ]);

            $shortUrl = url('/s/' . $shortCode);
            Log::info('Link encurtado criado com sucesso', [
                'short_url' => $shortUrl,
                'original_url' => $originalUrl,
                'expires_at' => $expiresAt,
            ]);
            return response()->json([
                'short_url' => $shortUrl,
                'original_url' => $originalUrl,
            ]);
        } catch (\Throwable $e) {
            Log::error('Erro ao encurtar link', ['exception' => $e]);
            return response()->json(['error' => 'Erro interno ao encurtar link'], 500);
        }
    }

    /**
     * Redireciona para a URL original a partir do código curto
     * @param string $code
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function redirect($code)
    {
        try {
            Log::info('Tentando redirecionar para link original', ['short_code' => $code]);
            $link = Link::where('short_code', $code)->first();

            if (!$link) {
                Log::warning('Link não encontrado para código', ['short_code' => $code]);
                return response()->json(['error' => 'Link não encontrado'], 404);
            }

            // Verifica se o link expirou
            if ($link->expires_at && $link->expires_at->isPast()) {
                Log::info('Link expirado', ['short_code' => $code, 'expires_at' => $link->expires_at]);
                if (request()->expectsJson()) {
                    return response()->json(['error' => 'Link expirado'], 410);
                }
                abort(410, 'Link expirado');
            }

            $link->increment('clicks');
            Log::info('Redirecionando para URL original', ['short_code' => $code, 'original_url' => $link->original_url]);

            return Redirect::to($link->original_url);
        } catch (\Throwable $e) {
            Log::error('Erro ao redirecionar link', ['exception' => $e, 'short_code' => $code]);
            return response()->json(['error' => 'Erro interno ao redirecionar link'], 500);
        }
    }
}
