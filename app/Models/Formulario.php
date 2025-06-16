<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Formulario extends Model
{
   use HasFactory;
   protected $fillable = [
      'titulo',
      'estrutura', // JSON com a estrutura dos campos customizados
   ];
   protected $casts = [
      'estrutura' => 'array',
   ];

   public function getHashSlugAttribute()
   {
      // Gera um hash seguro (ex: base64url do id + slug)
      $hash = rtrim(strtr(base64_encode(pack('N', $this->id)), '+/', '-_'), '=');
      $slug = Str::slug($this->titulo);
      return $hash . '-' . $slug;
   }
   public static function findByHashSlug($hashSlug)
   {
      $parts = explode('-', $hashSlug, 2);
      $hash = $parts[0] ?? null;
      if (!$hash) return null;
      $bin = base64_decode(strtr($hash, '-_', '+/') . '==');
      if ($bin === false || strlen($bin) !== 4) return null;
      $id = unpack('N', $bin)[1] ?? null;
      return $id ? self::find($id) : null;
   }
}
