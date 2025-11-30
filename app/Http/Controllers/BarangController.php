<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $barang = Barang::latest()->get();
        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:100',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|integer|min:0',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi' => 'nullable|string|max:255',
        ]);

        $validated['kode_barang'] = Barang::generateKode();
        $barang = Barang::create($validated);
        
        LogAktivitas::catat('create', 'barang', $barang->id, null, $barang->toArray());

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Barang $barang)
    {
        $barang->load(['detailPeminjaman.peminjaman.operator']);
        return view('barang.show', compact('barang'));
    }

    public function edit(Barang $barang)
    {
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kategori' => 'nullable|string|max:100',
            'satuan' => 'required|string|max:50',
            'stok' => 'required|integer|min:0',
            'kondisi' => 'required|in:baik,rusak_ringan,rusak_berat',
            'lokasi' => 'nullable|string|max:255',
        ]);

        $dataLama = $barang->toArray();
        $barang->update($validated);
        
        LogAktivitas::catat('update', 'barang', $barang->id, $dataLama, $barang->toArray());

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        if ($barang->detailPeminjaman()->exists()) {
            return back()->with('error', 'Barang tidak dapat dihapus karena memiliki riwayat peminjaman.');
        }

        $dataLama = $barang->toArray();
        $barang->delete();
        
        LogAktivitas::catat('delete', 'barang', $barang->id, $dataLama, null);

        return redirect()->route('barang.index')
            ->with('success', 'Barang berhasil dihapus.');
    }
}
