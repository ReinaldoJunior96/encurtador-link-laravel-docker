<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class StatusController extends Controller
{
    /**
     * Retorna o status da aplicação
     * @return \Illuminate\Http\JsonResponse
     */
    public function status()
    {
        return response()->json(['status' => 'ok'], 200);
    }
}
