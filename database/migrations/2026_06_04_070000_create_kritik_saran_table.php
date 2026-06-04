<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kritik_saran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->string('judul');
            $table->text('isi_kritik');
            $table->enum('kategori', ['kritik', 'saran', 'aspirasi'])->default('saran');
            $table->enum('status', ['menunggu', 'ditanggapi', 'selesai'])->default('menunggu');
            $table->text('tanggapan')->nullable();
            $table->foreignId('id_petugas')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('tanggapan_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kritik_saran');
    }
};
