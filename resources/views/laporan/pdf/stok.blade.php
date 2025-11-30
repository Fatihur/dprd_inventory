<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Stok Barang</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>SEKRETARIAT DPRD KABUPATEN SUMBAWA</h2>
        <p>Bagian Umum</p>
        <p><strong>LAPORAN STOK BARANG INVENTARIS</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="30">No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Satuan</th>
                <th class="text-center">Stok</th>
                <th>Kondisi</th>
                <th>Lokasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barang as $b)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $b->kode_barang }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ $b->kategori ?? '-' }}</td>
                <td>{{ $b->satuan }}</td>
                <td class="text-center">{{ $b->stok }}</td>
                <td>{{ $b->kondisi_label }}</td>
                <td>{{ $b->lokasi ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Sumbawa, {{ now()->translatedFormat('d F Y') }}</p>
        <br><br><br>
        <p>(_________________________)</p>
    </div>
</body>
</html>
