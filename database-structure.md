# Database Structure - Sistem Inventaris DPRD

## Table: users

| Field | Type | Length | Extra | Null |
|-------|------|--------|-------|------|
| id | BIGINT | 20 | PRIMARY KEY AUTO_INCREMENT | NO |
| name | VARCHAR | 255 | | NO |
| email | VARCHAR | 255 | UNIQUE | NO |
| email_verified_at | TIMESTAMP | | NULLABLE | YES |
| password | VARCHAR | 255 | | NO |
| role | ENUM | 'admin','operator','kabag_umum' | DEFAULT 'operator' | NO |
| remember_token | VARCHAR | 100 | NULLABLE | YES |

## Table: barang

| Field | Type | Length | Extra | Null |
|-------|------|--------|-------|------|
| id | BIGINT | 20 | PRIMARY KEY AUTO_INCREMENT | NO |
| kode_barang | VARCHAR | 255 | UNIQUE | NO |
| nama_barang | VARCHAR | 255 | | NO |
| deskripsi | TEXT | | NULLABLE | YES |
| kategori | VARCHAR | 255 | NULLABLE | YES |
| satuan | VARCHAR | 255 | | NO |
| stok | INT | 11 | DEFAULT 0 | NO |
| kondisi | ENUM | 'baik','rusak_ringan','rusak_berat' | DEFAULT 'baik' | NO |
| lokasi | VARCHAR | 255 | NULLABLE | YES |

## Table: peminjaman

| Field | Type | Length | Extra | Null |
|-------|------|--------|-------|------|
| id | BIGINT | 20 | PRIMARY KEY AUTO_INCREMENT | NO |
| kode_peminjaman | VARCHAR | 255 | UNIQUE | NO |
| operator_id | BIGINT | 20 | FOREIGN KEY (users.id) | NO |
| kepala_bagian_id | BIGINT | 20 | FOREIGN KEY (users.id) NULLABLE | YES |
| nama_peminjam | VARCHAR | 255 | | NO |
| unit_kerja | VARCHAR | 255 | | NO |
| keperluan | TEXT | | NULLABLE | YES |
| tanggal_pinjam | DATE | | | NO |
| tanggal_kembali_rencana | DATE | | | NO |
| tanggal_kembali_aktual | DATE | | NULLABLE | YES |
| status | ENUM | 'pending','approved','rejected','dipinjam','selesai' | DEFAULT 'pending' | NO |
| alasan_penolakan | TEXT | | NULLABLE | YES |
| catatan_pengembalian | TEXT | | NULLABLE | YES |
| bukti_peminjaman | VARCHAR | 255 | NULLABLE (file path/URL) | YES |
| bukti_pengembalian | VARCHAR | 255 | NULLABLE (file path/URL) | YES |

## Table: detail_peminjaman

| Field | Type | Length | Extra | Null |
|-------|------|--------|-------|------|
| id | BIGINT | 20 | PRIMARY KEY AUTO_INCREMENT | NO |
| peminjaman_id | BIGINT | 20 | FOREIGN KEY (peminjaman.id) | NO |
| barang_id | BIGINT | 20 | FOREIGN KEY (barang.id) | NO |
| jumlah | INT | 11 | | NO |
| jumlah_kembali | INT | 11 | DEFAULT 0 | NO |
| kondisi_kembali | ENUM | 'baik','rusak_ringan','rusak_berat' | NULLABLE | YES |

## Table: log_aktivitas

| Field | Type | Length | Extra | Null |
|-------|------|--------|-------|------|
| id | BIGINT | 20 | PRIMARY KEY AUTO_INCREMENT | NO |
| user_id | BIGINT | 20 | FOREIGN KEY (users.id) | NO |
| aksi | VARCHAR | 255 | | NO |
| tabel | VARCHAR | 255 | | NO |
| record_id | BIGINT | 20 | NULLABLE | YES |
| data_lama | TEXT | | NULLABLE | YES |
| data_baru | TEXT | | NULLABLE | YES |
| ip_address | VARCHAR | 255 | NULLABLE | YES |

## Table: notifikasi

| Field | Type | Length | Extra | Null |
|-------|------|--------|-------|------|
| id | BIGINT | 20 | PRIMARY KEY AUTO_INCREMENT | NO |
| user_id | BIGINT | 20 | FOREIGN KEY (users.id) | NO |
| judul | VARCHAR | 255 | | NO |
| pesan | TEXT | | | NO |
| tipe | VARCHAR | 255 | DEFAULT 'info' | NO |
| link | VARCHAR | 255 | NULLABLE | YES |
| dibaca_pada | TIMESTAMP | | NULLABLE | YES |

---

## Relationships

### users
- Has Many: peminjaman (as operator)
- Has Many: peminjaman (as kepala_bagian)
- Has Many: log_aktivitas
- Has Many: notifikasi

### barang
- Has Many: detail_peminjaman

### peminjaman
- Belongs To: users (operator_id)
- Belongs To: users (kepala_bagian_id)
- Has Many: detail_peminjaman

### detail_peminjaman
- Belongs To: peminjaman
- Belongs To: barang

### log_aktivitas
- Belongs To: users

### notifikasi
- Belongs To: users

---

## Enum Values

### users.role
- admin
- operator
- kabag_umum

### barang.kondisi
- baik
- rusak_ringan
- rusak_berat

### peminjaman.status
- pending
- approved
- rejected
- dipinjam
- selesai

### detail_peminjaman.kondisi_kembali
- baik
- rusak_ringan
- rusak_berat

### notifikasi.tipe
- info
- success
- warning
- danger
