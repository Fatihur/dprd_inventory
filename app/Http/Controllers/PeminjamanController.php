<?php

namespace App\Http\Controllers;

use App\Mail\PeminjamanJatuhTempoMail;
use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use App\Models\LogAktivitas;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
            'email_peminjam' => 'required|email|max:255',
            'no_hp_peminjam' => 'nullable|string|max:20',
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
                'email_peminjam' => $validated['email_peminjam'],
                'no_hp_peminjam' => $validated['no_hp_peminjam'] ?? null,
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

    public function kirimNotifikasiJatuhTempo(Peminjaman $peminjaman)
    {
        // Validasi: hanya bisa kirim jika status dipinjam dan ada email
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Notifikasi hanya bisa dikirim untuk peminjaman dengan status "Sedang Dipinjam".');
        }

        if (!$peminjaman->email_peminjam) {
            return back()->with('error', 'Email peminjam tidak tersedia.');
        }

        $peminjaman->load('detailPeminjaman.barang');

        // Tentukan tipe notifikasi
        $isOverdue = $peminjaman->tanggal_kembali_rencana->isPast();
        $type = $isOverdue ? 'overdue' : 'reminder';

        try {
            Mail::to($peminjaman->email_peminjam)->send(new PeminjamanJatuhTempoMail($peminjaman, $type));
            
            $peminjaman->update(['notifikasi_jatuh_tempo_dikirim' => now()]);

            $message = $isOverdue 
                ? "Email notifikasi keterlambatan berhasil dikirim ke {$peminjaman->email_peminjam}"
                : "Email reminder jatuh tempo berhasil dikirim ke {$peminjaman->email_peminjam}";

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}
