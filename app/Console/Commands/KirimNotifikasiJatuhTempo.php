<?php

namespace App\Console\Commands;

use App\Mail\PeminjamanJatuhTempoMail;
use App\Models\Peminjaman;
use App\Models\Notifikasi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class KirimNotifikasiJatuhTempo extends Command
{
    protected $signature = 'peminjaman:kirim-notifikasi-jatuh-tempo';
    protected $description = 'Kirim notifikasi email untuk peminjaman yang akan/sudah jatuh tempo';

    public function handle()
    {
        $this->info('Memulai pengecekan peminjaman jatuh tempo...');

        // 1. Kirim reminder H-3 sebelum jatuh tempo
        $this->kirimReminder();

        // 2. Kirim notifikasi untuk yang sudah melewati jatuh tempo
        $this->kirimOverdue();

        $this->info('Selesai.');
    }

    private function kirimReminder()
    {
        $tanggalH3 = now()->addDays(3)->toDateString();

        $peminjaman = Peminjaman::where('status', 'dipinjam')
            ->whereDate('tanggal_kembali_rencana', $tanggalH3)
            ->whereNotNull('email_peminjam')
            ->whereNull('notifikasi_jatuh_tempo_dikirim')
            ->with('detailPeminjaman.barang')
            ->get();

        foreach ($peminjaman as $p) {
            try {
                Mail::to($p->email_peminjam)->send(new PeminjamanJatuhTempoMail($p, 'reminder'));
                
                $p->update(['notifikasi_jatuh_tempo_dikirim' => now()]);

                // Kirim notifikasi ke operator juga
                Notifikasi::kirim(
                    $p->operator_id,
                    'Reminder Jatuh Tempo',
                    "Peminjaman {$p->kode_peminjaman} akan jatuh tempo dalam 3 hari. Email reminder telah dikirim ke peminjam.",
                    'warning',
                    route('peminjaman.show', $p)
                );

                $this->info("✓ Reminder dikirim ke: {$p->email_peminjam} ({$p->kode_peminjaman})");
            } catch (\Exception $e) {
                $this->error("✗ Gagal kirim ke {$p->email_peminjam}: {$e->getMessage()}");
            }
        }

        $this->info("Total reminder dikirim: {$peminjaman->count()}");
    }

    private function kirimOverdue()
    {
        $peminjaman = Peminjaman::where('status', 'dipinjam')
            ->whereDate('tanggal_kembali_rencana', '<', now())
            ->whereNotNull('email_peminjam')
            ->with('detailPeminjaman.barang')
            ->get();

        foreach ($peminjaman as $p) {
            // Kirim email overdue setiap 3 hari sekali
            $lastSent = $p->notifikasi_jatuh_tempo_dikirim;
            if ($lastSent && now()->diffInDays($lastSent) < 3) {
                continue;
            }

            try {
                Mail::to($p->email_peminjam)->send(new PeminjamanJatuhTempoMail($p, 'overdue'));
                
                $p->update(['notifikasi_jatuh_tempo_dikirim' => now()]);

                // Kirim notifikasi ke operator dan admin
                Notifikasi::kirimKeBanyakRole(
                    ['admin', 'operator'],
                    'Peminjaman Terlambat',
                    "Peminjaman {$p->kode_peminjaman} oleh {$p->nama_peminjam} sudah melewati jatuh tempo. Email notifikasi telah dikirim.",
                    'danger',
                    route('peminjaman.show', $p)
                );

                $this->info("✓ Overdue dikirim ke: {$p->email_peminjam} ({$p->kode_peminjaman})");
            } catch (\Exception $e) {
                $this->error("✗ Gagal kirim ke {$p->email_peminjam}: {$e->getMessage()}");
            }
        }

        $this->info("Total overdue dikirim: {$peminjaman->count()}");
    }
}
