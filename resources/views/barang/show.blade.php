@extends('layouts.app')

@section('title', 'Detail Barang')
@section('page-title', 'Detail Barang')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-box-seam me-2"></i>Informasi Barang
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted" width="40%">Kode</td>
                        <td><code>{{ $barang->kode_barang }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama</td>
                        <td>{{ $barang->nama_barang }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kategori</td>
                        <td>{{ $barang->kategori ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Satuan</td>
                        <td>{{ $barang->satuan }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Stok</td>
                        <td><span class="badge {{ $barang->stok <= 5 ? 'bg-danger' : 'bg-success' }}">{{ $barang->stok }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Stok Tersedia</td>
                        <td><span class="badge bg-info">{{ $barang->stok_tersedia }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kondisi</td>
                        <td>
                            @if($barang->kondisi === 'baik')
                            <span class="badge bg-success">Baik</span>
                            @elseif($barang->kondisi === 'rusak_ringan')
                            <span class="badge bg-warning">Rusak Ringan</span>
                            @else
                            <span class="badge bg-danger">Rusak Berat</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Lokasi</td>
                        <td>{{ $barang->lokasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Deskripsi</td>
                        <td>{{ $barang->deskripsi ?? '-' }}</td>
                    </tr>
                </table>
                <div class="d-flex gap-2">
                    <a href="{{ route('barang.edit', $barang) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                    <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clock-history me-2"></i>Riwayat Peminjaman
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="dataTable">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Peminjam</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barang->detailPeminjaman as $detail)
                            <tr>
                                <td>{{ $detail->peminjaman->kode_peminjaman }}</td>
                                <td>{{ $detail->peminjaman->nama_peminjam }}</td>
                                <td>{{ $detail->jumlah }} {{ $barang->satuan }}</td>
                                <td>{{ $detail->peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td><span class="badge bg-{{ $detail->peminjaman->status_badge }}">{{ $detail->peminjaman->status_label }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        }
    });
});
</script>
@endpush
