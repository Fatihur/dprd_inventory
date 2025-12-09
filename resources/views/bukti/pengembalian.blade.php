<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pengembalian - {{ $peminjaman->kode_peminjaman }}</title>
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
        
        .kondisi-baik {
            color: green;
            font-weight: bold;
        }
        
        .kondisi-rusak {
            color: red;
            font-weight: bold;
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
            background: #28a745;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn-print:hover {
            background: #218838;
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
            <h3 style="font-size: 18px; text-decoration: underline;">BUKTI PENGEMBALIAN BARANG</h3>
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
                <span class="info-label">Tanggal Kembali Rencana</span>
                <span class="info-value">{{ $peminjaman->tanggal_kembali_rencana->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Kembali Aktual</span>
                <span class="info-value">{{ $peminjaman->tanggal_kembali_aktual->format('d/m/Y') }}</span>
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
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 35%;">Nama Barang</th>
                    <th style="width: 15%;" class="text-center">Dipinjam</th>
                    <th style="width: 15%;" class="text-center">Dikembalikan</th>
                    <th style="width: 15%;" class="text-center">Satuan</th>
                    <th style="width: 15%;" class="text-center">Kondisi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjaman->detailPeminjaman as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $detail->barang->nama_barang }}</td>
                    <td class="text-center">{{ $detail->jumlah }}</td>
                    <td class="text-center">{{ $detail->jumlah_kembali }}</td>
                    <td class="text-center">{{ $detail->barang->satuan }}</td>
                    <td class="text-center">
                        <span class="{{ $detail->kondisi_kembali === 'baik' ? 'kondisi-baik' : 'kondisi-rusak' }}">
                            {{ strtoupper(str_replace('_', ' ', $detail->kondisi_kembali ?? 'BAIK')) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-row">
                <span>TOTAL BARANG DIKEMBALIKAN</span>
                <span>{{ $peminjaman->detailPeminjaman->sum('jumlah_kembali') }} Unit</span>
            </div>
        </div>

        @if($peminjaman->catatan_pengembalian)
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Catatan</span>
                <span class="info-value">{{ $peminjaman->catatan_pengembalian }}</span>
            </div>
        </div>
        @endif

        <div class="footer">
            <div class="notes">
                <strong>Keterangan:</strong><br>
                - Barang telah diterima kembali dalam kondisi seperti tertera di atas<br>
                - Peminjam telah menyelesaikan kewajiban pengembalian barang
            </div>

            <div class="signature-section">
                <div class="signature-box">
                    <p style="margin-bottom: 10px;">Yang Mengembalikan,</p>
                    <div class="signature-line">
                        {{ $peminjaman->nama_peminjam }}
                    </div>
                </div>
                <div class="signature-box">
                    <p style="margin-bottom: 10px;">Penerima,</p>
                    <div class="signature-line">
                        {{ $peminjaman->operator->name }}
                    </div>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 20px; font-size: 11px; color: #666;">
            Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>
</body>
</html>
