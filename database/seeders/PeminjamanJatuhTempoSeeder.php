<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Database\Seeder;

class PeminjamanJatuhTempoSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil operator dan kabag
        $operator = User::where('role', 'operator')->first();
        $kabag = User::where('role', 'kabag_umum')->first();
        $barang = Barang::first();

        if (!$operator || !$kabag || !$barang) {
            $this->command->error('Pastikan sudah ada data user (operator, kabag) dan barang!');
            return;
        }

        // Buat peminjaman yang sudah jatuh tempo (tanggal kembali kemarin)
        $peminjaman = Peminjaman::create([
            'kode_peminjaman' => 'PJM-' . date('Ymd') . '-TEST',
            'operator_id' => $operator->id,
            'kepala_bagian_id' => $kabag->id,
            'nama_peminjam' => 'Fatihur Rahman',
            'email_peminjam' => 'fatihur17@gmail.com',
            'no_hp_peminjam' => '081234567890',
            'unit_kerja' => 'Bagian IT',
            'keperluan' => 'Testing notifikasi jatuh tempo',
            'tanggal_pinjam' => now()->subDays(7)->toDateString(),
            'tanggal_kembali_rencana' => now()->subDays(1)->toDateString(), // Sudah jatuh tempo kemarin
            'status' => 'dipinjam', // Status masih dipinjam
        ]);

        // Tambah detail barang yang dipinjam
        DetailPeminjaman::create([
            'peminjaman_id' => $peminjaman->id,
            'barang_id' => $barang->id,
            'jumlah' => 1,
        ]);

        $this->command->info("✓ Peminjaman jatuh tempo berhasil dibuat:");
        $this->command->info("  Kode: {$peminjaman->kode_peminjaman}");
        $this->command->info("  Peminjam: {$peminjaman->nama_peminjam}");
        $this->command->info("  Email: {$peminjaman->email_peminjam}");
        $this->command->info("  Jatuh Tempo: {$peminjaman->tanggal_kembali_rencana->format('d/m/Y')}");
    }
}
