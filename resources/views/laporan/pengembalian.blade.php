@extends('layouts.app')

@section('title', 'Laporan Pengembalian')
@section('page-title', 'Laporan Pengembalian')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-file-earmark-check me-2"></i>Laporan Pengembalian</span>
        <a href="{{ route('laporan.pengembalian.pdf', request()->query()) }}" class="btn btn-danger btn-sm">
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
                        <th>Peminjam</th>
                        <th>Unit Kerja</th>
                        <th>Barang</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman as $p)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><code>{{ $p->kode_peminjaman }}</code></td>
                        <td>{{ $p->nama_peminjam }}</td>
                        <td>{{ $p->unit_kerja }}</td>
                        <td>
                            <small>
                                @foreach($p->detailPeminjaman as $detail)
                                {{ $detail->barang->nama_barang }} ({{ $detail->jumlah_kembali }}/{{ $detail->jumlah }}){{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </small>
                        </td>
                        <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>{{ $p->tanggal_kembali_aktual ? $p->tanggal_kembali_aktual->format('d/m/Y') : '-' }}</td>
                        <td>
                            @php
                                $kondisi = $p->detailPeminjaman->pluck('kondisi_kembali')->unique();
                            @endphp
                            @if($kondisi->contains('rusak_berat'))
                            <span class="badge bg-danger">Ada Rusak Berat</span>
                            @elseif($kondisi->contains('rusak_ringan'))
                            <span class="badge bg-warning">Ada Rusak Ringan</span>
                            @else
                            <span class="badge bg-success">Semua Baik</span>
                            @endif
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
        order: [[6, 'desc']],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
        }
    });
});
</script>
@endpush
