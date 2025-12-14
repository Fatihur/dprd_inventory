<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->string('email_peminjam')->nullable()->after('nama_peminjam');
            $table->string('no_hp_peminjam')->nullable()->after('email_peminjam');
            $table->timestamp('notifikasi_jatuh_tempo_dikirim')->nullable()->after('catatan_pengembalian');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['email_peminjam', 'no_hp_peminjam', 'notifikasi_jatuh_tempo_dikirim']);
        });
    }
};
