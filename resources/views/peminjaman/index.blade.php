@extends('layouts.app')

@section('title', 'Peminjaman')
@section('page-title', 'Daftar Peminjaman')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-arrow-up-right-square me-2"></i>Daftar Peminjaman</span>
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Ajukan Peminjaman
        </a>
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
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ $p->kode_peminjaman }}</code></td>
                        <td>{{ $p->nama_peminjam }}</td>
                        <td>{{ $p->unit_kerja }}</td>
                        <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                        <td><span class="badge bg-{{ $p->status_badge }}">{{ $p->status_label }}</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('peminjaman.show', $p) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($p->status === 'approved')
                                <form action="{{ route('peminjaman.serahkan', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Serahkan barang kepada peminjam?')">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success" title="Serahkan Barang">
                                        <i class="bi bi-box-arrow-right"></i>
                                    </button>
                                </form>
                                @endif
                                @if(in_array($p->status, ['approved', 'dipinjam', 'selesai']))
                                <a href="{{ route('bukti.peminjaman', $p) }}" class="btn btn-outline-primary" title="Cetak Bukti" target="_blank">
                                    <i class="bi bi-printer"></i>
                                </a>
                                @endif
                            </div>
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
