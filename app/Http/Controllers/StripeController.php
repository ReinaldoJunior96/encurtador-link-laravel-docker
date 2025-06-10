<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\LinkEncurtadoMail;
use App\Models\Link;

class StripeController extends Controller
{
    public function checkout(Request $request)
    {
        try {
            Stripe::setApiKey(env('STRIPE_SECRET'));

            $originalUrl = $request->input('url');

            if (!$originalUrl) {
                return response()->json(['error' => 'URL original é obrigatória.'], 422);
            }

            $session = StripeSession::create([
                'customer_email' => 'reinaldojunior272@gmail.com',
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $request->input('price_id', 'price_1RTNu8PNlX9GGGhmlO3wEoRd'),
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => $request->input('success_url', url('/success?session_id={CHECKOUT_SESSION_ID}&original_url=' . urlencode($originalUrl))),
                'cancel_url' => $request->input('cancel_url', url('/cancel')),
            ]);

            return redirect()->away($session->url);
        } catch (\Exception $e) {
            Log::error('❌ Erro ao criar sessão de checkout Stripe', ['exception' => $e]);
            return response()->json(['error' => 'Erro ao criar checkout'], 500);
        }
    }

    public function success(Request $request)
    {
        $sessionId = $request->input('session_id');
        $originalUrl = $request->input('original_url');
        $shortUrl = null;

        if (!$sessionId || !$originalUrl) {
            Log::warning('❌ Parâmetros ausentes na success', compact('sessionId', 'originalUrl'));
            return redirect('/')->with('error', 'Sessão ou URL inválida.');
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = StripeSession::retrieve($sessionId);

            if (!$session || !isset($session->payment_status)) {
                Log::error('❌ Sessão Stripe inválida ou não encontrada', ['session_id' => $sessionId]);
                return redirect('/')->with('error', 'Sessão Stripe inválida.');
            }

            if ($session->payment_status !== 'paid') {
                Log::warning('⚠️ Pagamento não confirmado', [
                    'session_id' => $sessionId,
                    'payment_status' => $session->payment_status,
                ]);
                return redirect('/')->with('error', 'Pagamento não confirmado.');
            }

            // Evita gerar duplicado se link já existir
            $existing = Link::where('original_url', $originalUrl)
                ->where('expires_at', '>=', now())
                ->latest()
                ->first();

            if ($existing) {
                Log::info('🔁 Link reaproveitado', ['short_code' => $existing->short_code]);
                $shortUrl = url('api/s/' . $existing->short_code);
            } else {
                do {
                    $shortCode = \Illuminate\Support\Str::random(6);
                } while (Link::where('short_code', $shortCode)->exists());

                $expiresAt = now()->addMinutes(10);

                $link = Link::create([
                    'original_url' => $originalUrl,
                    'short_code' => $shortCode,
                    'expires_at' => $expiresAt,
                ]);

                $shortUrl = url('api/s/' . $shortCode);
                Log::info('✅ Link criado com sucesso', [
                    'short_code' => $shortCode,
                    'original_url' => $originalUrl,
                ]);
            }

            // Envio de e-mail
            $customerEmail = $session->customer_email ?? 'reinaldojunior272@gmail.com';

            try {
                Mail::to($customerEmail)->send(new LinkEncurtadoMail($shortUrl, $originalUrl));
                Log::info('📧 E-mail enviado com sucesso', [
                    'email' => $customerEmail,
                    'short_url' => $shortUrl,
                ]);
            } catch (\Exception $e) {
                Log::error('❌ Falha ao enviar e-mail', ['exception' => $e]);
            }

            return view('welcome', compact('shortUrl'));
        } catch (\Exception $e) {
            Log::error('❌ Erro ao processar rota success', ['exception' => $e]);
            return redirect('/')->with('error', 'Erro ao processar pagamento.');
        }
    }
}
