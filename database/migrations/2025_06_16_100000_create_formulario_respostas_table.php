<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
   public function up(): void
   {
      Schema::create('formulario_respostas', function (Blueprint $table) {
         $table->id();
         $table->foreignId('formulario_id')->constrained('formularios')->onDelete('cascade');
         $table->json('respostas');
         $table->timestamps();
      });
   }

   public function down(): void
   {
      Schema::dropIfExists('formulario_respostas');
   }
};
