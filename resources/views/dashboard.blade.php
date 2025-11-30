@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-value">{{ $totalBarang }}</div>
                    <div class="stat-label">Total Jenis Barang</div>
                </div>
                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-box-seam"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-value">{{ $totalStok }}</div>
                    <div class="stat-label">Total Unit Barang</div>
                </div>
                <div class="stat-icon bg-success bg-opacity-10 text-success">
                    <i class="bi bi-boxes"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-value">{{ $peminjamanPending }}</div>
                    <div class="stat-label">Menunggu Persetujuan</div>
                </div>
                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-value">{{ $peminjamanAktif }}</div>
                    <div class="stat-label">Sedang Dipinjam</div>
                </div>
                <div class="stat-icon bg-info bg-opacity-10 text-info">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-graph-up me-2"></i>Statistik Peminjaman & Pengembalian</span>
                <span class="text-muted small">6 Bulan Terakhir</span>
            </div>
            <div class="card-body">
                <canvas id="chartPeminjaman" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Peminjaman Terbaru
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($peminjamanTerbaru as $p)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold">{{ $p->kode_peminjaman }}</div>
                                <small class="text-muted">{{ $p->nama_peminjam }} - {{ $p->unit_kerja }}</small>
                            </div>
                            <span class="badge bg-{{ $p->status_badge }}">{{ $p->status_label }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <p class="mb-0 mt-2">Belum ada peminjaman</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->isAdmin() && isset($barangHampirHabis) && $barangHampirHabis->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning bg-opacity-10">
                <i class="bi bi-exclamation-triangle me-2 text-warning"></i>Peringatan: Barang dengan Stok Hampir Habis
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangHampirHabis as $b)
                            <tr>
                                <td>{{ $b->kode_barang }}</td>
                                <td>{{ $b->nama_barang }}</td>
                                <td>{{ $b->kategori ?? '-' }}</td>
                                <td><span class="badge bg-danger">{{ $b->stok }}</span></td>
                                <td>
                                    <a href="{{ route('barang.edit', $b) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartPeminjaman').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartData['labels']),
        datasets: [{
            label: 'Peminjaman',
            data: @json($chartData['peminjaman']),
            backgroundColor: 'rgba(50, 31, 219, 0.8)',
            borderRadius: 5
        }, {
            label: 'Pengembalian',
            data: @json($chartData['pengembalian']),
            backgroundColor: 'rgba(46, 184, 92, 0.8)',
            borderRadius: 5
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
@endpush
