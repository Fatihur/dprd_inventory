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

        $barang2 = Barang::skip(1)->first() ?? $barang;

        // Peminjaman 1 - Jatuh tempo kemarin
        $peminjaman1 = Peminjaman::create([
            'kode_peminjaman' => 'PJM-' . date('Ymd') . '-TEST1',
            'operator_id' => $operator->id,
            'kepala_bagian_id' => $kabag->id,
            'nama_peminjam' => 'Nina Samita',
            'email_peminjam' => 'ninasasmitaa@gmail.com',
            'no_hp_peminjam' => '081234567890',
            'unit_kerja' => 'Bagian IT',
            'keperluan' => 'Testing notifikasi jatuh tempo',
            'tanggal_pinjam' => now()->subDays(7)->toDateString(),
            'tanggal_kembali_rencana' => now()->subDays(1)->toDateString(),
            'status' => 'dipinjam',
        ]);

        DetailPeminjaman::create([
            'peminjaman_id' => $peminjaman1->id,
            'barang_id' => $barang->id,
            'jumlah' => 1,
        ]);

        // Peminjaman 2 - Jatuh tempo 3 hari lalu
        $peminjaman2 = Peminjaman::create([
            'kode_peminjaman' => 'PJM-' . date('Ymd') . '-TEST2',
            'operator_id' => $operator->id,
            'kepala_bagian_id' => $kabag->id,
            'nama_peminjam' => 'Fatihur Rahman',
            'email_peminjam' => 'fatihur17@gmail.com',
            'no_hp_peminjam' => '089876543210',
            'unit_kerja' => 'Bagian Keuangan',
            'keperluan' => 'Rapat koordinasi',
            'tanggal_pinjam' => now()->subDays(10)->toDateString(),
            'tanggal_kembali_rencana' => now()->subDays(3)->toDateString(),
            'status' => 'dipinjam',
        ]);

        DetailPeminjaman::create([
            'peminjaman_id' => $peminjaman2->id,
            'barang_id' => $barang2->id,
            'jumlah' => 2,
        ]);

        $this->command->info("✓ Peminjaman jatuh tempo berhasil dibuat:");
        $this->command->info("");
        $this->command->info("  1. Kode: {$peminjaman1->kode_peminjaman}");
        $this->command->info("     Peminjam: {$peminjaman1->nama_peminjam}");
        $this->command->info("     Email: {$peminjaman1->email_peminjam}");
        $this->command->info("     Jatuh Tempo: {$peminjaman1->tanggal_kembali_rencana->format('d/m/Y')}");
        $this->command->info("");
        $this->command->info("  2. Kode: {$peminjaman2->kode_peminjaman}");
        $this->command->info("     Peminjam: {$peminjaman2->nama_peminjam}");
        $this->command->info("     Email: {$peminjaman2->email_peminjam}");
        $this->command->info("     Jatuh Tempo: {$peminjaman2->tanggal_kembali_rencana->format('d/m/Y')}");
    }
}
