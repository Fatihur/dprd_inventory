@extends('layouts.app')

@section('title', 'Detail Permohonan')
@section('page-title', 'Detail Permohonan Peminjaman')

@section('content')
<div class="row">
    <div class="col-lg-5">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Informasi Peminjaman
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="40%">Kode</td>
                        <td><code>{{ $peminjaman->kode_peminjaman }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td><span class="badge bg-{{ $peminjaman->status_badge }}">{{ $peminjaman->status_label }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama Peminjam</td>
                        <td>{{ $peminjaman->nama_peminjam }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Unit Kerja</td>
                        <td>{{ $peminjaman->unit_kerja }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Keperluan</td>
                        <td>{{ $peminjaman->keperluan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Pinjam</td>
                        <td>{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Rencana Kembali</td>
                        <td>{{ $peminjaman->tanggal_kembali_rencana->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Diajukan Oleh</td>
                        <td>{{ $peminjaman->operator->name }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Pengajuan</td>
                        <td>{{ $peminjaman->created_at->format('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($peminjaman->status === 'pending')
        <div class="d-flex gap-2 mb-4">
            <form action="{{ route('persetujuan.approve', $peminjaman) }}" method="POST" onsubmit="return confirm('Setujui peminjaman ini?')">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-lg me-1"></i>Setujui
                </button>
            </form>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalTolak">
                <i class="bi bi-x-lg me-1"></i>Tolak
            </button>
            <a href="{{ route('persetujuan.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <div class="modal fade" id="modalTolak" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('persetujuan.reject', $peminjaman) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tolak Peminjaman</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="alasan" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="alasan" name="alasan_penolakan" rows="3" required minlength="10" placeholder="Masukkan alasan penolakan..."></textarea>
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
        @else
        <a href="{{ route('persetujuan.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        @endif
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-box-seam me-2"></i>Daftar Barang yang Dimohon
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Stok Tersedia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjaman->detailPeminjaman as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><code>{{ $detail->barang->kode_barang }}</code></td>
                                <td>{{ $detail->barang->nama_barang }}</td>
                                <td>{{ $detail->jumlah }} {{ $detail->barang->satuan }}</td>
                                <td>
                                    <span class="badge {{ $detail->barang->stok_tersedia >= $detail->jumlah ? 'bg-success' : 'bg-danger' }}">
                                        {{ $detail->barang->stok_tersedia }}
                                    </span>
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
@endsection
