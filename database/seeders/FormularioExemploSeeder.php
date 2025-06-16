<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Formulario;

class FormularioExemploSeeder extends Seeder
{
   public function run()
   {
      $estrutura = [
         ['label' => 'Nome Completo', 'type' => 'text'],
         ['label' => 'E-mail', 'type' => 'text'],
         ['label' => 'Idade', 'type' => 'number'],
         ['label' => 'Gênero', 'type' => 'radio'],
         ['label' => 'Áreas de Interesse', 'type' => 'checkbox'],
         ['label' => 'Departamento', 'type' => 'select'],
         ['label' => 'Está satisfeito?', 'type' => 'radio'],
         ['label' => 'Comentários', 'type' => 'textarea'],
         ['label' => 'Data de Ingresso', 'type' => 'text'],
         ['label' => 'Feedback sobre o RH', 'type' => 'textarea'],
      ];
      Formulario::create([
         'titulo' => 'Formulário Exemplo Completo',
         'estrutura' => json_encode($estrutura),
      ]);
   }
}
