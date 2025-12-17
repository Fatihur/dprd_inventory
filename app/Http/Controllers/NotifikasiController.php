<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);
        
        return view('notifikasi.index', compact('notifikasi'));
    }

    public function getUnread()
    {
        $notifikasi = Notifikasi::where('user_id', auth()->id())
            ->whereNull('dibaca_pada')
            ->latest()
            ->take(5)
            ->get();
        
        $count = Notifikasi::where('user_id', auth()->id())
            ->whereNull('dibaca_pada')
            ->count();

        return response()->json([
            'count' => $count,
            'notifikasi' => $notifikasi->map(fn($n) => [
                'id' => $n->id,
                'judul' => $n->judul,
                'pesan' => $n->pesan,
                'tipe' => $n->tipe,
                'icon' => $n->icon,
                'link' => $n->link,
                'waktu' => $n->created_at->diffForHumans(),
            ])
        ]);
    }

    public function tandaiDibaca(Notifikasi $notifikasi)
    {
        if ($notifikasi->user_id !== auth()->id()) {
            abort(403);
        }

        $notifikasi->tandaiDibaca();

        if ($notifikasi->link) {
            // Cek apakah link bisa diakses oleh user saat ini
            try {
                return redirect($notifikasi->link);
            } catch (\Exception $e) {
                return redirect()->route('notifikasi.index')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tujuan notifikasi.');
            }
        }

        return back();
    }

    public function tandaiSemuaDibaca()
    {
        Notifikasi::where('user_id', auth()->id())
            ->whereNull('dibaca_pada')
            ->update(['dibaca_pada' => now()]);

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
