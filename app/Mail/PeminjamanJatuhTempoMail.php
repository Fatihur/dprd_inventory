<?php

namespace App\Mail;

use App\Models\Peminjaman;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PeminjamanJatuhTempoMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Peminjaman $peminjaman,
        public string $tipe = 'reminder' // reminder, overdue
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->tipe === 'overdue' 
            ? '[PENTING] Peminjaman Barang Telah Melewati Jatuh Tempo - ' . $this->peminjaman->kode_peminjaman
            : '[Pengingat] Peminjaman Barang Akan Jatuh Tempo - ' . $this->peminjaman->kode_peminjaman;

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.peminjaman-jatuh-tempo',
        );
    }
}
