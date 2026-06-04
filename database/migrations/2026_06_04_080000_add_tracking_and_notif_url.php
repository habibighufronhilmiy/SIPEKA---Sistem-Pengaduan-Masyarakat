<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->string('kode_tracking', 20)->unique()->nullable()->after('id');
        });

        Schema::table('notifikasis', function (Blueprint $table) {
            $table->string('url')->nullable()->after('tipe');
        });
    }

    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn('kode_tracking');
        });

        Schema::table('notifikasis', function (Blueprint $table) {
            $table->dropColumn('url');
        });
    }
};
