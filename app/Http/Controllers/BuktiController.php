<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BuktiController extends Controller
{
    public function cetakBuktiPeminjaman(Peminjaman $peminjaman)
    {
        $peminjaman->load(['operator', 'kepalaBagian', 'detailPeminjaman.barang']);
        
        return view('bukti.peminjaman', compact('peminjaman'));
    }

    public function cetakBuktiPeminjamanPdf(Peminjaman $peminjaman)
    {
        $peminjaman->load(['operator', 'kepalaBagian', 'detailPeminjaman.barang']);
        
        $pdf = Pdf::loadView('bukti.peminjaman-pdf', compact('peminjaman'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('Bukti-Peminjaman-' . $peminjaman->kode_peminjaman . '.pdf');
    }

    public function cetakBuktiPengembalian(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'selesai') {
            return back()->with('error', 'Bukti pengembalian hanya bisa dicetak untuk peminjaman yang sudah selesai.');
        }

        $peminjaman->load(['operator', 'kepalaBagian', 'detailPeminjaman.barang']);
        
        return view('bukti.pengembalian', compact('peminjaman'));
    }

    public function cetakBuktiPengembalianPdf(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'selesai') {
            return back()->with('error', 'Bukti pengembalian hanya bisa dicetak untuk peminjaman yang sudah selesai.');
        }

        $peminjaman->load(['operator', 'kepalaBagian', 'detailPeminjaman.barang']);
        
        $pdf = Pdf::loadView('bukti.pengembalian-pdf', compact('peminjaman'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->stream('Bukti-Pengembalian-' . $peminjaman->kode_peminjaman . '.pdf');
    }
}
