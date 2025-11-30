@extends('layouts.app')

@section('title', 'Proses Pengembalian')
@section('page-title', 'Proses Pengembalian Barang')

@section('content')
<div class="row">
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-info-circle me-2"></i>Informasi Peminjaman
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <td class="text-muted">Kode</td>
                        <td><code>{{ $peminjaman->kode_peminjaman }}</code></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Peminjam</td>
                        <td>{{ $peminjaman->nama_peminjam }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Unit Kerja</td>
                        <td>{{ $peminjaman->unit_kerja }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tgl Pinjam</td>
                        <td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Rencana Kembali</td>
                        <td>
                            {{ $peminjaman->tanggal_kembali_rencana->format('d/m/Y') }}
                            @if($peminjaman->tanggal_kembali_rencana->isPast())
                            <span class="badge bg-danger">Terlambat</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-arrow-return-left me-2"></i>Form Pengembalian
            </div>
            <div class="card-body">
                <form action="{{ route('pengembalian.store', $peminjaman) }}" method="POST">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="tanggal_kembali" class="form-label">Tanggal Kembali <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_kembali') is-invalid @enderror" id="tanggal_kembali" name="tanggal_kembali" value="{{ old('tanggal_kembali', date('Y-m-d')) }}" required>
                            @error('tanggal_kembali')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h6 class="mb-3">Detail Barang yang Dikembalikan</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Barang</th>
                                    <th width="100">Dipinjam</th>
                                    <th width="120">Dikembalikan</th>
                                    <th width="160">Kondisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($peminjaman->detailPeminjaman as $detail)
                                <tr>
                                    <td>
                                        {{ $detail->barang->nama_barang }}
                                        <input type="hidden" name="detail[{{ $loop->index }}][id]" value="{{ $detail->id }}">
                                    </td>
                                    <td class="text-center">{{ $detail->jumlah }} {{ $detail->barang->satuan }}</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm" 
                                            name="detail[{{ $loop->index }}][jumlah_kembali]" 
                                            value="{{ $detail->jumlah }}" 
                                            min="0" 
                                            max="{{ $detail->jumlah }}" 
                                            required>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm" name="detail[{{ $loop->index }}][kondisi_kembali]" required>
                                            <option value="baik">Baik</option>
                                            <option value="rusak_ringan">Rusak Ringan</option>
                                            <option value="rusak_berat">Rusak Berat</option>
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mb-4">
                        <label for="catatan_pengembalian" class="form-label">Catatan Pengembalian</label>
                        <textarea class="form-control" id="catatan_pengembalian" name="catatan_pengembalian" rows="2" placeholder="Catatan tambahan (opsional)">{{ old('catatan_pengembalian') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i>Proses Pengembalian
                        </button>
                        <a href="{{ route('pengembalian.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
