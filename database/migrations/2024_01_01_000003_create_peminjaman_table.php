<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kepala_bagian_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nama_peminjam');
            $table->string('unit_kerja');
            $table->text('keperluan')->nullable();
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali_rencana');
            $table->date('tanggal_kembali_aktual')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'dipinjam', 'selesai'])->default('pending');
            $table->text('alasan_penolakan')->nullable();
            $table->text('catatan_pengembalian')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
