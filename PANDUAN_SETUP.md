# Sistem Informasi Peminjaman Inventaris Barang
## Sekretariat DPRD Kabupaten Sumbawa

### Persyaratan Sistem
- PHP >= 8.2
- MySQL >= 5.7
- Composer

### Langkah Setup

#### 1. Buat Database MySQL
```sql
CREATE DATABASE inventaris_dprd;
```

#### 2. Konfigurasi Database
Edit file `.env` dan sesuaikan konfigurasi database:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventaris_dprd
DB_USERNAME=root
DB_PASSWORD=
```

#### 3. Jalankan Migration & Seeder
```bash
php artisan migrate
php artisan db:seed
```

#### 4. Jalankan Server
```bash
php artisan serve
```

Akses aplikasi di: http://localhost:8000

### Akun Default

| Role | Email | Password |
|------|-------|----------|
| Administrator | admin@dprd.go.id | password |
| Operator | operator@dprd.go.id | password |
| Kepala Bagian Umum | kabag@dprd.go.id | password |

### Fitur Aplikasi

#### Role: Administrator
- Dashboard statistik
- Manajemen pengguna (CRUD)
- Manajemen barang inventaris (CRUD)
- Peminjaman barang
- Pengembalian barang
- Laporan (peminjaman, pengembalian, stok)
- Export PDF

#### Role: Operator
- Dashboard
- Pengajuan peminjaman
- Penyerahan barang yang sudah disetujui
- Pengembalian barang

#### Role: Kepala Bagian Umum
- Dashboard
- Persetujuan/penolakan peminjaman
- Laporan (peminjaman, pengembalian, stok)
- Export PDF

### Struktur Database
- `users` - Data pengguna sistem
- `barang` - Data inventaris barang
- `peminjaman` - Transaksi peminjaman
- `detail_peminjaman` - Detail barang yang dipinjam
- `log_aktivitas` - Audit log aktivitas sistem

### Catatan Penting
- Semua password di-hash menggunakan bcrypt
- Stok barang otomatis berkurang saat barang diserahkan
- Stok barang otomatis bertambah saat barang dikembalikan
- Laporan dapat di-export ke format PDF
