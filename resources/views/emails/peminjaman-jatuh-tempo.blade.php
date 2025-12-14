<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: {{ $tipe === 'overdue' ? '#dc3545' : '#ffc107' }};
            color: {{ $tipe === 'overdue' ? '#fff' : '#333' }};
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .info-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .info-table td:first-child {
            font-weight: bold;
            width: 40%;
            color: #666;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .items-table th, .items-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .items-table th {
            background: #e9ecef;
        }
        .footer {
            background: #343a40;
            color: #fff;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 12px;
        }
        .alert-box {
            background: {{ $tipe === 'overdue' ? '#f8d7da' : '#fff3cd' }};
            border: 1px solid {{ $tipe === 'overdue' ? '#f5c6cb' : '#ffeeba' }};
            color: {{ $tipe === 'overdue' ? '#721c24' : '#856404' }};
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>
            @if($tipe === 'overdue')
                ⚠️ PEMINJAMAN MELEWATI JATUH TEMPO
            @else
                🔔 PENGINGAT PENGEMBALIAN BARANG
            @endif
        </h2>
    </div>

    <div class="content">
        <p>Yth. <strong>{{ $peminjaman->nama_peminjam }}</strong>,</p>

        @if($tipe === 'overdue')
        <div class="alert-box">
            <strong>Perhatian!</strong> Peminjaman barang Anda telah melewati tanggal jatuh tempo. 
            Mohon segera mengembalikan barang yang dipinjam ke Sekretariat DPRD Kabupaten Sumbawa.
        </div>
        @else
        <div class="alert-box">
            <strong>Pengingat!</strong> Peminjaman barang Anda akan jatuh tempo dalam waktu dekat. 
            Mohon persiapkan pengembalian barang sesuai jadwal.
        </div>
        @endif

        <h3>Detail Peminjaman:</h3>
        <table class="info-table">
            <tr>
                <td>Kode Peminjaman</td>
                <td><strong>{{ $peminjaman->kode_peminjaman }}</strong></td>
            </tr>
            <tr>
                <td>Unit Kerja</td>
                <td>{{ $peminjaman->unit_kerja }}</td>
            </tr>
            <tr>
                <td>Tanggal Pinjam</td>
                <td>{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Tanggal Jatuh Tempo</td>
                <td><strong style="color: {{ $tipe === 'overdue' ? '#dc3545' : '#ffc107' }}">{{ $peminjaman->tanggal_kembali_rencana->format('d F Y') }}</strong></td>
            </tr>
            @if($tipe === 'overdue')
            <tr>
                <td>Keterlambatan</td>
                <td><strong style="color: #dc3545">{{ now()->diffInDays($peminjaman->tanggal_kembali_rencana) }} hari</strong></td>
            </tr>
            @else
            <tr>
                <td>Sisa Waktu</td>
                <td><strong>{{ $peminjaman->tanggal_kembali_rencana->diffInDays(now()) }} hari lagi</strong></td>
            </tr>
            @endif
        </table>

        <h3>Barang yang Dipinjam:</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Satuan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman->detailPeminjaman as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $detail->barang->nama_barang }}</td>
                    <td>{{ $detail->jumlah }}</td>
                    <td>{{ $detail->barang->satuan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <p>Untuk pengembalian barang, silakan hubungi:</p>
        <ul>
            <li><strong>Bagian Umum Sekretariat DPRD</strong></li>
            <li>Telp: (0371) 21234</li>
            <li>Alamat: Jl. Garuda No. 1, Sumbawa Besar</li>
        </ul>

        <p>Terima kasih atas perhatian dan kerjasamanya.</p>

        <p>Hormat kami,<br>
        <strong>Sekretariat DPRD Kabupaten Sumbawa</strong></p>
    </div>

    <div class="footer">
        <p>Email ini dikirim secara otomatis oleh Sistem Informasi Peminjaman Inventaris Barang DPRD.<br>
        Mohon tidak membalas email ini.</p>
    </div>
</body>
</html>
