<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['to_id']);
            $table->unsignedBigInteger('to_id')->nullable()->change();
        });
    }
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('to_id')->nullable(false)->change();
            $table->foreign('to_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
