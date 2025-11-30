<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; }
        .periode { margin-bottom: 15px; }
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
        <p><strong>LAPORAN PEMINJAMAN BARANG</strong></p>
    </div>

    <div class="periode">
        <strong>Periode:</strong> {{ $periode }}
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="30">No</th>
                <th>Kode</th>
                <th>Peminjam</th>
                <th>Unit Kerja</th>
                <th>Barang</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $p)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $p->kode_peminjaman }}</td>
                <td>{{ $p->nama_peminjam }}</td>
                <td>{{ $p->unit_kerja }}</td>
                <td>
                    @foreach($p->detailPeminjaman as $detail)
                    {{ $detail->barang->nama_barang }} ({{ $detail->jumlah }}){{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </td>
                <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                <td>{{ $p->status_label }}</td>
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
