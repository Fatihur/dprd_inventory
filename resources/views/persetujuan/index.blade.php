@extends('layouts.app')

@section('title', 'Persetujuan Peminjaman')
@section('page-title', 'Persetujuan Peminjaman')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-clipboard-check me-2"></i>Daftar Permohonan Peminjaman
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
                        <td><span class="badge bg-{{ $p->status_badge }}">{{ $p->status_label }}</span></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('persetujuan.show', $p) }}" class="btn btn-outline-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($p->status === 'pending')
                                <form action="{{ route('persetujuan.approve', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Setujui peminjaman ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success" title="Setujui">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-outline-danger" title="Tolak" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $p->id }}">
                                    <i class="bi bi-x-lg"></i>
                                </button>
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

@foreach($peminjaman as $p)
@if($p->status === 'pending')
<div class="modal fade" id="modalTolak{{ $p->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('persetujuan.reject', $p) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan menolak peminjaman <strong>{{ $p->kode_peminjaman }}</strong></p>
                    <div class="mb-3">
                        <label for="alasan{{ $p->id }}" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="alasan{{ $p->id }}" name="alasan_penolakan" rows="3" required minlength="10" placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Peminjaman</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endforeach
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
