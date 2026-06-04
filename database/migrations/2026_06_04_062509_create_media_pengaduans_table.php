<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_pengaduans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengaduan')->constrained('pengaduans')->cascadeOnDelete();
            $table->string('file_path');
            $table->enum('file_type', ['foto', 'video'])->default('foto');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_pengaduans');
    }
};
