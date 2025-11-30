<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function peminjaman(Request $request)
    {
        $peminjaman = Peminjaman::with(['operator', 'kepalaBagian', 'detailPeminjaman.barang'])
            ->latest()
            ->get();
        
        return view('laporan.peminjaman', compact('peminjaman'));
    }

    public function peminjamanPdf(Request $request)
    {
        $query = Peminjaman::with(['operator', 'kepalaBagian', 'detailPeminjaman.barang']);

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->sampai_tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->latest()->get();
        $periode = $this->getPeriode($request);

        $pdf = Pdf::loadView('laporan.pdf.peminjaman', compact('peminjaman', 'periode'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('laporan-peminjaman-' . date('Y-m-d') . '.pdf');
    }

    public function pengembalian(Request $request)
    {
        $peminjaman = Peminjaman::with(['operator', 'detailPeminjaman.barang'])
            ->where('status', 'selesai')
            ->latest()
            ->get();
        
        return view('laporan.pengembalian', compact('peminjaman'));
    }

    public function pengembalianPdf(Request $request)
    {
        $query = Peminjaman::with(['operator', 'detailPeminjaman.barang'])
            ->where('status', 'selesai');

        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal_kembali_aktual', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal_kembali_aktual', '<=', $request->sampai_tanggal);
        }

        $peminjaman = $query->latest()->get();
        $periode = $this->getPeriode($request);

        $pdf = Pdf::loadView('laporan.pdf.pengembalian', compact('peminjaman', 'periode'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('laporan-pengembalian-' . date('Y-m-d') . '.pdf');
    }

    public function stok(Request $request)
    {
        $barang = Barang::orderBy('nama_barang')->get();
        
        return view('laporan.stok', compact('barang'));
    }

    public function stokPdf(Request $request)
    {
        $query = Barang::query();

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $barang = $query->orderBy('nama_barang')->get();

        $pdf = Pdf::loadView('laporan.pdf.stok', compact('barang'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('laporan-stok-barang-' . date('Y-m-d') . '.pdf');
    }

    private function getPeriode($request)
    {
        $dari = $request->dari_tanggal ? date('d/m/Y', strtotime($request->dari_tanggal)) : 'Awal';
        $sampai = $request->sampai_tanggal ? date('d/m/Y', strtotime($request->sampai_tanggal)) : 'Sekarang';
        return "$dari - $sampai";
    }
}
