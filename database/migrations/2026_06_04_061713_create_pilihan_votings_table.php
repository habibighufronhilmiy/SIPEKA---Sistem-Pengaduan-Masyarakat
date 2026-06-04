<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pilihan_votings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_voting')->constrained('votings')->cascadeOnDelete();
            $table->string('pilihan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pilihan_votings');
    }
};
