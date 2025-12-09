<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Peminjaman - {{ $peminjaman->kode_peminjaman }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .struk {
            border: 2px dashed #333;
            padding: 20px;
            background: #fff;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 16px;
            font-weight: normal;
            margin-bottom: 10px;
        }
        
        .kode {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .info-section {
            margin: 15px 0;
            border-bottom: 1px dashed #666;
            padding-bottom: 10px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 14px;
        }
        
        .info-label {
            font-weight: bold;
            width: 40%;
        }
        
        .info-value {
            width: 60%;
            text-align: right;
        }
        
        .items-table {
            width: 100%;
            margin: 15px 0;
            border-collapse: collapse;
        }
        
        .items-table th {
            border-bottom: 2px solid #333;
            padding: 8px 5px;
            text-align: left;
            font-size: 13px;
        }
        
        .items-table td {
            padding: 8px 5px;
            border-bottom: 1px dashed #999;
            font-size: 13px;
        }
        
        .items-table .text-right {
            text-align: right;
        }
        
        .items-table .text-center {
            text-align: center;
        }
        
        .total-section {
            margin: 15px 0;
            padding: 10px 0;
            border-top: 2px solid #333;
            border-bottom: 2px solid #333;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px dashed #666;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }
        
        .signature-box {
            text-align: center;
            width: 45%;
        }
        
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 5px;
            font-size: 12px;
        }
        
        .notes {
            margin-top: 20px;
            font-size: 12px;
            font-style: italic;
        }
        
        .print-button {
            text-align: center;
            margin: 20px 0;
        }
        
        .btn-print {
            background: #007bff;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-print:hover {
            background: #0056b3;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .print-button {
                display: none;
            }
            
            .struk {
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="print-button">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak Bukti</button>
        <a href="{{ route('peminjaman.show', $peminjaman) }}" class="btn-print" style="background: #6c757d; text-decoration: none; display: inline-block;">← Kembali</a>
    </div>

    <div class="struk">
        <div class="header">
            <h1>SEKRETARIAT DPRD</h1>
            <h2>KABUPATEN SUMBAWA</h2>
            <p style="font-size: 12px;">Jl. Garuda No. 1, Sumbawa Besar</p>
            <p style="font-size: 12px;">Telp: (0371) 21234</p>
        </div>

        <div style="text-align: center; margin: 15px 0;">
            <h3 style="font-size: 18px; text-decoration: underline;">BUKTI PEMINJAMAN BARANG</h3>
        </div>

        <div class="kode">
            No: {{ $peminjaman->kode_peminjaman }}
        </div>

        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Tanggal Pinjam</span>
                <span class="info-value">{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Kembali</span>
                <span class="info-value">{{ $peminjaman->tanggal_kembali_rencana->format('d/m/Y') }}</span>
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Nama Peminjam</span>
                <span class="info-value">{{ $peminjaman->nama_peminjam }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Unit Kerja</span>
                <span class="info-value">{{ $peminjaman->unit_kerja }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Keperluan</span>
                <span class="info-value">{{ $peminjaman->keperluan ?? '-' }}</span>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 45%;">Nama Barang</th>
                    <th style="width: 20%;" class="text-center">Kode</th>
                    <th style="width: 15%;" class="text-center">Jumlah</th>
                    <th style="width: 15%;" class="text-center">Satuan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman->detailPeminjaman as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $detail->barang->nama_barang }}</td>
                    <td class="text-center">{{ $detail->barang->kode_barang }}</td>
                    <td class="text-center">{{ $detail->jumlah }}</td>
                    <td class="text-center">{{ $detail->barang->satuan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row">
                <span>TOTAL BARANG</span>
                <span>{{ $peminjaman->detailPeminjaman->count() }} Item ({{ $peminjaman->detailPeminjaman->sum('jumlah') }} Unit)</span>
            </div>
        </div>

        <div class="footer">
            <div class="notes">
                <strong>Catatan:</strong><br>
                - Barang yang dipinjam harus dikembalikan sesuai tanggal yang tertera<br>
                - Peminjam bertanggung jawab atas kerusakan atau kehilangan barang<br>
                - Bukti ini harus dibawa saat pengembalian barang
            </div>

            <div class="signature-section">
                <div class="signature-box">
                    <p style="margin-bottom: 10px;">Peminjam,</p>
                    <div class="signature-line">
                        {{ $peminjaman->nama_peminjam }}
                    </div>
                </div>
                <div class="signature-box">
                    <p style="margin-bottom: 10px;">Petugas,</p>
                    <div class="signature-line">
                        {{ $peminjaman->operator->name }}
                    </div>
                </div>
            </div>

            @if($peminjaman->kepalaBagian)
            <div style="text-align: center; margin-top: 30px;">
                <p style="margin-bottom: 10px;">Mengetahui,<br>Kepala Bagian Umum</p>
                <div class="signature-line" style="width: 200px; margin: 60px auto 0;">
                    {{ $peminjaman->kepalaBagian->name }}
                </div>
            </div>
            @endif
        </div>

        <div style="text-align: center; margin-top: 20px; font-size: 11px; color: #666;">
            Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>
</body>
</html>
