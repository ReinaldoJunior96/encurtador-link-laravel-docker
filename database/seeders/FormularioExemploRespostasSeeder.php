<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Formulario;
use App\Models\FormularioResposta;
use Illuminate\Support\Str;

class FormularioExemploRespostasSeeder extends Seeder
{
   public function run()
   {
      $form = Formulario::where('titulo', 'Formulário Exemplo Completo')->first();
      if (!$form) return;
      $estrutura = is_array($form->estrutura) ? $form->estrutura : json_decode($form->estrutura, true);
      $generos = ['Masculino', 'Feminino', 'Outro'];
      $departamentos = ['RH', 'TI', 'Financeiro', 'Marketing', 'Vendas'];
      $interesses = ['Tecnologia', 'Gestão', 'Saúde', 'Educação', 'Finanças'];
      for ($i = 0; $i < 500; $i++) {
         $respostas = [
            fake()->name(),
            fake()->safeEmail(),
            rand(18, 65),
            $generos[array_rand($generos)],
            array_rand(array_flip($interesses), rand(1, 3)),
            $departamentos[array_rand($departamentos)],
            rand(0, 1) ? 'Sim' : 'Não',
            fake()->sentence(8),
            fake()->date('d/m/Y'),
            fake()->realText(40),
         ];
         // Checkbox pode ser string se só um valor, garantir array
         if (!is_array($respostas[4])) $respostas[4] = [$respostas[4]];
         FormularioResposta::create([
            'formulario_id' => $form->id,
            'respostas' => json_encode($respostas),
         ]);
      }
   }
}
