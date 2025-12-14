@extends('layouts.app')

@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Peminjaman')

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
                    @if($peminjaman->tanggal_kembali_aktual)
                    <tr>
                        <td class="text-muted">Tanggal Kembali</td>
                        <td>{{ $peminjaman->tanggal_kembali_aktual->format('d F Y') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted">Operator</td>
                        <td>{{ $peminjaman->operator->name }}</td>
                    </tr>
                    @if($peminjaman->kepalaBagian)
                    <tr>
                        <td class="text-muted">Disetujui Oleh</td>
                        <td>{{ $peminjaman->kepalaBagian->name }}</td>
                    </tr>
                    @endif
                    @if($peminjaman->alasan_penolakan)
                    <tr>
                        <td class="text-muted">Alasan Penolakan</td>
                        <td class="text-danger">{{ $peminjaman->alasan_penolakan }}</td>
                    </tr>
                    @endif
                    @if($peminjaman->catatan_pengembalian)
                    <tr>
                        <td class="text-muted">Catatan Pengembalian</td>
                        <td>{{ $peminjaman->catatan_pengembalian }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <div class="d-flex gap-2 flex-wrap">
            @if($peminjaman->status === 'approved')
            <form action="{{ route('peminjaman.serahkan', $peminjaman) }}" method="POST" onsubmit="return confirm('Serahkan barang kepada peminjam?')">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-box-arrow-right me-1"></i>Serahkan Barang
                </button>
            </form>
            @endif
            
            @if(in_array($peminjaman->status, ['approved', 'dipinjam', 'selesai']))
            <a href="{{ route('bukti.peminjaman', $peminjaman) }}" class="btn btn-primary" target="_blank">
                <i class="bi bi-printer me-1"></i>Cetak Bukti Peminjaman
            </a>
            @endif
            
            @if($peminjaman->status === 'selesai')
            <a href="{{ route('bukti.pengembalian', $peminjaman) }}" class="btn btn-success" target="_blank">
                <i class="bi bi-printer me-1"></i>Cetak Bukti Pengembalian
            </a>
            @endif
            
            @if($peminjaman->status === 'dipinjam' && $peminjaman->email_peminjam)
            <form action="{{ route('peminjaman.kirim-notifikasi', $peminjaman) }}" method="POST" class="d-inline" onsubmit="return confirm('Kirim email notifikasi ke {{ $peminjaman->email_peminjam }}?')">
                @csrf
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-envelope me-1"></i>Kirim Email Notifikasi
                </button>
            </form>
            @endif
            
            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-box-seam me-2"></i>Daftar Barang
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
                                @if($peminjaman->status === 'selesai')
                                <th>Kembali</th>
                                <th>Kondisi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($peminjaman->detailPeminjaman as $detail)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><code>{{ $detail->barang->kode_barang }}</code></td>
                                <td>{{ $detail->barang->nama_barang }}</td>
                                <td>{{ $detail->jumlah }} {{ $detail->barang->satuan }}</td>
                                @if($peminjaman->status === 'selesai')
                                <td>{{ $detail->jumlah_kembali }} {{ $detail->barang->satuan }}</td>
                                <td>
                                    @if($detail->kondisi_kembali === 'baik')
                                    <span class="badge bg-success">Baik</span>
                                    @elseif($detail->kondisi_kembali === 'rusak_ringan')
                                    <span class="badge bg-warning">Rusak Ringan</span>
                                    @else
                                    <span class="badge bg-danger">Rusak Berat</span>
                                    @endif
                                </td>
                                @endif
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
