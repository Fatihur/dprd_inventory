<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\LogAktivitas;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersetujuanController extends Controller
{
    public function index(Request $request)
    {
        $peminjaman = Peminjaman::with(['operator', 'detailPeminjaman.barang'])
            ->latest()
            ->get();
        
        return view('persetujuan.index', compact('peminjaman'));
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['operator', 'detailPeminjaman.barang']);
        return view('persetujuan.show', compact('peminjaman'));
    }

    public function approve(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        DB::beginTransaction();
        try {
            $dataLama = $peminjaman->toArray();

            foreach ($peminjaman->detailPeminjaman as $detail) {
                $barang = $detail->barang;
                if ($barang->stok_tersedia < $detail->jumlah) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi.");
                }
            }

            $peminjaman->update([
                'status' => 'approved',
                'kepala_bagian_id' => auth()->id(),
            ]);

            LogAktivitas::catat('approve', 'peminjaman', $peminjaman->id, $dataLama, $peminjaman->toArray());

            // Kirim notifikasi ke Operator yang mengajukan
            Notifikasi::kirim(
                $peminjaman->operator_id,
                'Peminjaman Disetujui',
                "Peminjaman {$peminjaman->kode_peminjaman} telah disetujui. Silakan serahkan barang kepada peminjam.",
                'success',
                route('peminjaman.show', $peminjaman)
            );

            DB::commit();
            
            return redirect()->route('persetujuan.index')
                ->with('success', 'Peminjaman ' . $peminjaman->kode_peminjaman . ' berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'alasan_penolakan' => 'required|string|min:10',
        ]);

        $dataLama = $peminjaman->toArray();

        $peminjaman->update([
            'status' => 'rejected',
            'kepala_bagian_id' => auth()->id(),
            'alasan_penolakan' => $validated['alasan_penolakan'],
        ]);

        LogAktivitas::catat('reject', 'peminjaman', $peminjaman->id, $dataLama, $peminjaman->toArray());

        // Kirim notifikasi ke Operator yang mengajukan
        Notifikasi::kirim(
            $peminjaman->operator_id,
            'Peminjaman Ditolak',
            "Peminjaman {$peminjaman->kode_peminjaman} ditolak. Alasan: {$validated['alasan_penolakan']}",
            'danger',
            route('peminjaman.show', $peminjaman)
        );

        return redirect()->route('persetujuan.index')
            ->with('success', 'Peminjaman ' . $peminjaman->kode_peminjaman . ' berhasil ditolak.');
    }

    public function serahkan(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'approved') {
            return back()->with('error', 'Peminjaman belum disetujui atau sudah diproses.');
        }

        DB::beginTransaction();
        try {
            $dataLama = $peminjaman->toArray();

            foreach ($peminjaman->detailPeminjaman as $detail) {
                $barang = $detail->barang;
                $barang->decrement('stok', $detail->jumlah);
            }

            $peminjaman->update(['status' => 'dipinjam']);

            LogAktivitas::catat('serahkan', 'peminjaman', $peminjaman->id, $dataLama, $peminjaman->toArray());

            // Kirim notifikasi ke Admin dan Kabag
            Notifikasi::kirimKeBanyakRole(
                ['admin', 'kabag_umum'],
                'Barang Diserahkan',
                "Barang untuk peminjaman {$peminjaman->kode_peminjaman} telah diserahkan kepada {$peminjaman->nama_peminjam}.",
                'info',
                route('peminjaman.show', $peminjaman)
            );

            DB::commit();
            
            return redirect()->route('peminjaman.index')
                ->with('success', 'Barang berhasil diserahkan kepada peminjam.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
