@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')
@section('page-title', 'Ajukan Peminjaman')

@section('content')
<div class="card">
    <div class="card-header">
        <i class="bi bi-arrow-up-right-square me-2"></i>Form Pengajuan Peminjaman
    </div>
    <div class="card-body">
        <form action="{{ route('peminjaman.store') }}" method="POST" id="formPeminjaman">
            @csrf
         
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama_peminjam" class="form-label">Nama Peminjam <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_peminjam') is-invalid @enderror" id="nama_peminjam" name="nama_peminjam" value="{{ old('nama_peminjam') }}" required>
                        @error('nama_peminjam')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="unit_kerja" class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('unit_kerja') is-invalid @enderror" id="unit_kerja" name="unit_kerja" value="{{ old('unit_kerja') }}" required>
                        @error('unit_kerja')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror" id="tanggal_pinjam" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                        @error('tanggal_pinjam')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal_kembali_rencana" class="form-label">Tanggal Rencana Kembali <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_kembali_rencana') is-invalid @enderror" id="tanggal_kembali_rencana" name="tanggal_kembali_rencana" value="{{ old('tanggal_kembali_rencana') }}" required>
                        @error('tanggal_kembali_rencana')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="keperluan" class="form-label">Keperluan</label>
                <textarea class="form-control @error('keperluan') is-invalid @enderror" id="keperluan" name="keperluan" rows="2">{{ old('keperluan') }}</textarea>
                @error('keperluan')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <hr>
            <h6 class="mb-3"><i class="bi bi-box-seam me-2"></i>Daftar Barang yang Dipinjam</h6>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <select class="form-select" id="selectBarang">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barang as $b)
                        <option value="{{ $b->id }}" data-nama="{{ $b->nama_barang }}" data-satuan="{{ $b->satuan }}" data-stok="{{ $b->stok_tersedia }}">
                            {{ $b->kode_barang }} - {{ $b->nama_barang }} (Tersedia: {{ $b->stok_tersedia }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control" id="inputJumlah" placeholder="Jumlah" min="1">
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-success w-100" id="btnTambahBarang">
                        <i class="bi bi-plus-lg me-1"></i>Tambah
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="tableBarang">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Barang</th>
                            <th width="120">Jumlah</th>
                            <th width="100">Satuan</th>
                            <th width="80">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary" id="btnSubmit">
                    <i class="bi bi-send me-1"></i>Ajukan Peminjaman
                </button>
                <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
let barangList = [];
let itemIndex = 0;

document.getElementById('btnTambahBarang').addEventListener('click', function() {
    const select = document.getElementById('selectBarang');
    const jumlah = document.getElementById('inputJumlah');
    
    if (!select.value || !jumlah.value) {
        alert('Pilih barang dan masukkan jumlah!');
        return;
    }

    const option = select.options[select.selectedIndex];
    const stok = parseInt(option.dataset.stok);
    const jml = parseInt(jumlah.value);

    if (jml > stok) {
        alert('Jumlah melebihi stok tersedia!');
        return;
    }

    if (barangList.includes(select.value)) {
        alert('Barang sudah ada dalam daftar!');
        return;
    }

    barangList.push(select.value);
    
    const tbody = document.querySelector('#tableBarang tbody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>
            ${option.dataset.nama}
            <input type="hidden" name="barang[${itemIndex}][id]" value="${select.value}">
        </td>
        <td>
            <input type="number" class="form-control form-control-sm" name="barang[${itemIndex}][jumlah]" value="${jml}" min="1" max="${stok}" required>
        </td>
        <td>${option.dataset.satuan}</td>
        <td>
            <button type="button" class="btn btn-danger btn-sm btn-hapus" data-id="${select.value}">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    itemIndex++;

    select.value = '';
    jumlah.value = '';
});

document.querySelector('#tableBarang tbody').addEventListener('click', function(e) {
    if (e.target.closest('.btn-hapus')) {
        const btn = e.target.closest('.btn-hapus');
        const id = btn.dataset.id;
        barangList = barangList.filter(item => item !== id);
        btn.closest('tr').remove();
    }
});

document.getElementById('formPeminjaman').addEventListener('submit', function(e) {
    if (barangList.length === 0) {
        e.preventDefault();
        alert('Tambahkan minimal satu barang!');
    }
});
</script>
@endpush
