<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Formulario;

class FormularioController extends Controller
{
   public function index()
   {
      $formularios = Formulario::all();
      return view('rh.formulario.index', compact('formularios'));
   }

   public function create()
   {
      return view('rh.formulario.create');
   }

   public function store(Request $request)
   {
      $request->validate([
         'titulo' => 'required|string|max:255',
         'estrutura' => 'required|json',
      ]);
      $form = Formulario::create([
         'titulo' => $request->titulo,
         'estrutura' => $request->estrutura,
      ]);
      return redirect()->route('rh.formulario.index')->with('success', 'Formulário criado!');
   }

   public function edit($hashSlug)
   {
      $formulario = \App\Models\Formulario::findByHashSlug($hashSlug);
      if (!$formulario) abort(404);
      return view('rh.formulario.edit', compact('formulario'));
   }

   public function update(Request $request, $hashSlug)
   {
      $formulario = \App\Models\Formulario::findByHashSlug($hashSlug);
      if (!$formulario) abort(404);
      $request->validate([
         'titulo' => 'required|string|max:255',
         'estrutura' => 'required|json',
      ]);
      $formulario->update([
         'titulo' => $request->titulo,
         'estrutura' => $request->estrutura,
      ]);
      return redirect()->route('rh.formulario.index')->with('success', 'Formulário atualizado!');
   }

   public function destroy($hashSlug)
   {
      $formulario = \App\Models\Formulario::findByHashSlug($hashSlug);
      if (!$formulario) abort(404);
      $formulario->delete();
      return redirect()->route('rh.formulario.index')->with('success', 'Formulário removido!');
   }

   public function showPublic($hashSlug)
   {
      $formulario = \App\Models\Formulario::findByHashSlug($hashSlug);
      if (!$formulario) abort(404);
      return view('rh.formulario.public', compact('formulario'));
   }

   public function responder(Request $request, $hashSlug)
   {
      $formulario = \App\Models\Formulario::findByHashSlug($hashSlug);
      if (!$formulario) abort(404);
      $request->validate([
         'respostas' => 'required|array',
      ]);
      \App\Models\FormularioResposta::create([
         'formulario_id' => $formulario->id,
         'respostas' => json_encode($request->respostas),
      ]);
      return redirect()->route('formulario.public', $formulario->hashSlug)->with('success', 'Resposta enviada com sucesso!');
   }

   public function metricas($hashSlug)
   {
      $formulario = \App\Models\Formulario::findByHashSlug($hashSlug);
      if (!$formulario) abort(404);
      $estrutura = is_array($formulario->estrutura) ? $formulario->estrutura : json_decode($formulario->estrutura, true);
      if (!is_array($estrutura) || empty($estrutura)) {
         // Estrutura inválida ou não definida
         return view('rh.formulario.metricas', [
            'formulario' => $formulario,
            'respostas' => [],
            'resumo' => [],
            'estrutura' => [],
            'erro_estrutura' => true,
         ]);
      }
      $respostas = \App\Models\FormularioResposta::where('formulario_id', $formulario->id)->get();
      $resumo = [];
      foreach ($estrutura as $i => $campo) {
         $resumo[$i] = [
            'label' => $campo['label'] ?? 'Campo',
            'type' => $campo['type'] ?? 'text',
            'contagem' => [],
            'valores' => [], // Para textos
         ];
      }
      foreach ($respostas as $resp) {
         $dados = is_array($resp->respostas) ? $resp->respostas : json_decode($resp->respostas, true);
         foreach ($estrutura as $i => $campo) {
            $valor = $dados[$i] ?? null;
            if ($campo['type'] === 'checkbox' && is_array($valor)) {
               foreach ($valor as $v) {
                  $resumo[$i]['contagem'][$v] = ($resumo[$i]['contagem'][$v] ?? 0) + 1;
               }
            } elseif ($campo['type'] === 'radio' || $campo['type'] === 'select') {
               if ($valor !== null) {
                  $resumo[$i]['contagem'][$valor] = ($resumo[$i]['contagem'][$valor] ?? 0) + 1;
               }
            } elseif ($campo['type'] === 'number' && is_numeric($valor)) {
               $resumo[$i]['valores'][] = floatval($valor);
            } elseif (in_array($campo['type'], ['text', 'textarea'])) {
               if ($valor !== null && $valor !== '') {
                  $resumo[$i]['valores'][] = $valor;
               }
            }
         }
      }
      return view('rh.formulario.metricas', compact('formulario', 'respostas', 'resumo', 'estrutura'));
   }
}
