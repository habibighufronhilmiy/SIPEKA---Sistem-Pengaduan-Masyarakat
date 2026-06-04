<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voting_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_voting')->constrained('votings')->cascadeOnDelete();
            $table->foreignId('id_pilihan')->constrained('pilihan_votings')->cascadeOnDelete();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voting_user');
    }
};
