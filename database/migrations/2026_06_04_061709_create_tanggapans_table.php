<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanggapans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pengaduan')->constrained('pengaduans')->cascadeOnDelete();
            $table->foreignId('id_petugas')->constrained('users')->cascadeOnDelete();
            $table->dateTime('tgl_tanggapan');
            $table->text('isi_tanggapan');
            $table->string('bukti_foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanggapans');
    }
};
