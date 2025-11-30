<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $data = [
            'totalBarang' => Barang::count(),
            'totalStok' => Barang::sum('stok'),
            'peminjamanPending' => Peminjaman::where('status', 'pending')->count(),
            'peminjamanAktif' => Peminjaman::whereIn('status', ['approved', 'dipinjam'])->count(),
            'peminjamanSelesai' => Peminjaman::where('status', 'selesai')->count(),
            'peminjamanBulanIni' => Peminjaman::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        if ($user->isOperator()) {
            $data['peminjamanSaya'] = Peminjaman::where('operator_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
        }

        if ($user->isAdmin()) {
            $data['totalUsers'] = User::count();
            $data['barangHampirHabis'] = Barang::where('stok', '<=', 5)->get();
        }

        $data['peminjamanTerbaru'] = Peminjaman::with(['operator', 'detailPeminjaman.barang'])
            ->latest()
            ->take(5)
            ->get();

        $data['chartData'] = $this->getChartData();

        return view('dashboard', $data);
    }

    private function getChartData()
    {
        $months = [];
        $peminjamanData = [];
        $pengembalianData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->translatedFormat('M');
            
            $peminjamanData[] = Peminjaman::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $pengembalianData[] = Peminjaman::where('status', 'selesai')
                ->whereMonth('tanggal_kembali_aktual', $date->month)
                ->whereYear('tanggal_kembali_aktual', $date->year)
                ->count();
        }

        return [
            'labels' => $months,
            'peminjaman' => $peminjamanData,
            'pengembalian' => $pengembalianData,
        ];
    }
}
