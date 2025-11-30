<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\LogAktivitas;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['operator', 'detailPeminjaman.barang']);

        if (auth()->user()->isOperator()) {
            $query->where('operator_id', auth()->id());
        }

        $peminjaman = $query->latest()->get();
        
        return view('peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $barang = Barang::where('stok', '>', 0)->where('kondisi', 'baik')->get();
        return view('peminjaman.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_peminjam' => 'required|string|max:255',
            'unit_kerja' => 'required|string|max:255',
            'keperluan' => 'nullable|string',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after:tanggal_pinjam',
            'barang' => 'required|array|min:1',
            'barang.*.id' => 'required|exists:barang,id',
            'barang.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::create([
                'kode_peminjaman' => Peminjaman::generateKode(),
                'operator_id' => auth()->id(),
                'nama_peminjam' => $validated['nama_peminjam'],
                'unit_kerja' => $validated['unit_kerja'],
                'keperluan' => $validated['keperluan'],
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_kembali_rencana' => $validated['tanggal_kembali_rencana'],
                'status' => 'pending',
            ]);

            foreach ($validated['barang'] as $item) {
                $barang = Barang::findOrFail($item['id']);
                
                if ($barang->stok_tersedia < $item['jumlah']) {
                    throw new \Exception("Stok {$barang->nama_barang} tidak mencukupi. Tersedia: {$barang->stok_tersedia}");
                }

                DetailPeminjaman::create([
                    'peminjaman_id' => $peminjaman->id,
                    'barang_id' => $item['id'],
                    'jumlah' => $item['jumlah'],
                ]);
            }

            LogAktivitas::catat('create', 'peminjaman', $peminjaman->id, null, $peminjaman->load('detailPeminjaman')->toArray());

            // Kirim notifikasi ke Kepala Bagian Umum
            Notifikasi::kirimKeRole(
                'kabag_umum',
                'Pengajuan Peminjaman Baru',
                "Peminjaman {$peminjaman->kode_peminjaman} oleh {$peminjaman->nama_peminjam} menunggu persetujuan.",
                'warning',
                route('persetujuan.show', $peminjaman)
            );

            DB::commit();
            
            return redirect()->route('peminjaman.index')
                ->with('success', 'Pengajuan peminjaman berhasil dibuat dengan kode: ' . $peminjaman->kode_peminjaman);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['operator', 'kepalaBagian', 'detailPeminjaman.barang']);
        return view('peminjaman.show', compact('peminjaman'));
    }

    public function getBarangInfo(Barang $barang)
    {
        return response()->json([
            'id' => $barang->id,
            'kode_barang' => $barang->kode_barang,
            'nama_barang' => $barang->nama_barang,
            'satuan' => $barang->satuan,
            'stok_tersedia' => $barang->stok_tersedia,
        ]);
    }
}
