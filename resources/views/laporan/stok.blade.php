@extends('layouts.app')

@section('title', 'Laporan Stok')
@section('page-title', 'Laporan Stok Barang')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan Stok Barang</span>
        <a href="{{ route('laporan.stok.pdf', request()->query()) }}" class="btn btn-danger btn-sm">
            <i class="bi bi-file-pdf me-1"></i>Export PDF
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-sm align-middle" id="dataTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Stok Total</th>
                        <th>Stok Tersedia</th>
                        <th>Kondisi</th>
                        <th>Lokasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barang as $b)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ $b->kode_barang }}</code></td>
                        <td>{{ $b->nama_barang }}</td>
                        <td>{{ $b->kategori ?? '-' }}</td>
                        <td>{{ $b->satuan }}</td>
                        <td><span class="badge {{ $b->stok <= 5 ? 'bg-danger' : 'bg-success' }}">{{ $b->stok }}</span></td>
                        <td><span class="badge bg-info">{{ $b->stok_tersedia }}</span></td>
                        <td>
                            @if($b->kondisi === 'baik')
                            <span class="badge bg-success">Baik</span>
                            @elseif($b->kondisi === 'rusak_ringan')
                            <span class="badge bg-warning">Rusak Ringan</span>
                            @else
                            <span class="badge bg-danger">Rusak Berat</span>
                            @endif
                        </td>
                        <td>{{ $b->lokasi ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
