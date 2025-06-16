<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormularioResposta extends Model
{
   protected $fillable = [
      'formulario_id',
      'respostas', // JSON
   ];
   protected $casts = [
      'respostas' => 'array',
   ];
}
