<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Barang;
use App\Models\LogAktivitas;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['operator', 'detailPeminjaman.barang'])
            ->where('status', 'dipinjam');

        if (auth()->user()->isOperator()) {
            $query->where('operator_id', auth()->id());
        }

        $peminjaman = $query->latest()->get();
        
        return view('pengembalian.index', compact('peminjaman'));
    }

    public function create(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Peminjaman ini tidak dalam status dipinjam.');
        }

        $peminjaman->load(['operator', 'detailPeminjaman.barang']);
        return view('pengembalian.create', compact('peminjaman'));
    }

    public function store(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Peminjaman ini tidak dalam status dipinjam.');
        }

        $validated = $request->validate([
            'tanggal_kembali' => 'required|date',
            'catatan_pengembalian' => 'nullable|string',
            'detail' => 'required|array',
            'detail.*.id' => 'required|exists:detail_peminjaman,id',
            'detail.*.jumlah_kembali' => 'required|integer|min:0',
            'detail.*.kondisi_kembali' => 'required|in:baik,rusak_ringan,rusak_berat',
        ]);

        DB::beginTransaction();
        try {
            $dataLama = $peminjaman->load('detailPeminjaman')->toArray();

            foreach ($validated['detail'] as $item) {
                $detail = $peminjaman->detailPeminjaman()->findOrFail($item['id']);
                
                if ($item['jumlah_kembali'] > $detail->jumlah) {
                    throw new \Exception("Jumlah kembali melebihi jumlah pinjam untuk " . $detail->barang->nama_barang);
                }

                $detail->update([
                    'jumlah_kembali' => $item['jumlah_kembali'],
                    'kondisi_kembali' => $item['kondisi_kembali'],
                ]);

                $barang = $detail->barang;
                $barang->increment('stok', $item['jumlah_kembali']);

                if ($item['kondisi_kembali'] !== 'baik' && $item['jumlah_kembali'] > 0) {
                    $barang->update(['kondisi' => $item['kondisi_kembali']]);
                }
            }

            $peminjaman->update([
                'status' => 'selesai',
                'tanggal_kembali_aktual' => $validated['tanggal_kembali'],
                'catatan_pengembalian' => $validated['catatan_pengembalian'],
            ]);

            LogAktivitas::catat('pengembalian', 'peminjaman', $peminjaman->id, $dataLama, $peminjaman->load('detailPeminjaman')->toArray());

            // Kirim notifikasi ke Admin dan Kabag
            Notifikasi::kirimKeBanyakRole(
                ['admin', 'kabag_umum'],
                'Pengembalian Barang',
                "Barang untuk peminjaman {$peminjaman->kode_peminjaman} telah dikembalikan oleh {$peminjaman->nama_peminjam}.",
                'success',
                route('peminjaman.show', $peminjaman)
            );

            DB::commit();
            
            return redirect()->route('pengembalian.index')
                ->with('success', 'Pengembalian berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
