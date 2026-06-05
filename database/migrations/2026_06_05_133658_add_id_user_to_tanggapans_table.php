<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tanggapans', function (Blueprint $table) {
            $table->foreignId('id_user')->nullable()->after('id_petugas')->constrained('users')->cascadeOnDelete();
            $table->foreignId('id_petugas')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tanggapans', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_user');
            $table->foreignId('id_petugas')->nullable(false)->change();
        });
    }
};
