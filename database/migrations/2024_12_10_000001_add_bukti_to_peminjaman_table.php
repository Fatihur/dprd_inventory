<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->string('bukti_peminjaman')->nullable()->after('catatan_pengembalian');
            $table->string('bukti_pengembalian')->nullable()->after('bukti_peminjaman');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['bukti_peminjaman', 'bukti_pengembalian']);
        });
    }
};
