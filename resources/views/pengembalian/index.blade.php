@extends('layouts.app')

@section('title', 'Pengembalian')
@section('page-title', 'Pengembalian Barang')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-arrow-down-left-square me-2"></i>Daftar Barang yang Sedang Dipinjam
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="dataTable">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Kode</th>
                        <th>Peminjam</th>
                        <th>Unit Kerja</th>
                        <th>Barang</th>
                        <th>Tgl Pinjam</th>
                        <th>Rencana Kembali</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman as $p)
                    <tr class="{{ $p->tanggal_kembali_rencana->isPast() ? 'table-warning' : '' }}">
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ $p->kode_peminjaman }}</code></td>
                        <td>{{ $p->nama_peminjam }}</td>
                        <td>{{ $p->unit_kerja }}</td>
                        <td>
                            <small>
                                @foreach($p->detailPeminjaman->take(2) as $detail)
                                {{ $detail->barang->nama_barang }} ({{ $detail->jumlah }}){{ !$loop->last ? ', ' : '' }}
                                @endforeach
                                @if($p->detailPeminjaman->count() > 2)
                                <span class="text-muted">+{{ $p->detailPeminjaman->count() - 2 }} lainnya</span>
                                @endif
                            </small>
                        </td>
                        <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>
                            {{ $p->tanggal_kembali_rencana->format('d/m/Y') }}
                            @if($p->tanggal_kembali_rencana->isPast())
                            <span class="badge bg-danger">Terlambat</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pengembalian.create', $p) }}" class="btn btn-success btn-sm">
                                <i class="bi bi-arrow-return-left me-1"></i>Kembalikan
                            </a>
                        </td>
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
        order: [[0, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        },
        columnDefs: [
            { orderable: false, targets: -1 }
        ]
    });
});
</script>
@endpush
