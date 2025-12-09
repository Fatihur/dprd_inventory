# Sequence Diagram - Sistem Informasi Peminjaman Inventaris Barang DPRD

## File Diagram

### Sequence Diagram
- **sequence-diagrams.puml** - Sequence diagram dengan tema default
- **sequence-diagrams-themed.puml** - Sequence diagram dengan berbagai pilihan tema

### Activity Diagram
- **activity-diagrams.puml** - Activity diagram detail dengan partition & fork/repeat
- **activity-diagrams-simple.puml** - Activity diagram dengan partition horizontal
- **activity-diagrams-classic.puml** - Activity diagram classic tanpa partition (seperti contoh gambar)

### Class Diagram
- **class-diagram.puml** - Class diagram lengkap dengan relasi dan controllers

## Cara Mengubah Tema

### Langkah-langkah:

1. **Buka file** `sequence-diagrams-themed.puml`
2. **Cari bagian tema** di bagian atas file
3. **Comment tema yang aktif** dengan menambahkan `/'` dan `'/` di sekitarnya
4. **Uncomment tema yang diinginkan** dengan menghapus `/'` dan `'/`
5. **Tekan Alt+D** untuk preview diagram dengan tema baru

### Contoh Mengubah Tema:

**Dari:**
```plantuml
!theme bluegray

/' 2. SKETCHY - Casual & Hand-drawn Style
!theme sketchy
'/
```

**Menjadi:**
```plantuml
/' 1. BLUEGRAY - Professional & Modern
!theme bluegray
'/

/' 2. SKETCHY - Casual & Hand-drawn Style '/
!theme sketchy
```

### Tema yang Tersedia:

| Tema | Deskripsi | Cocok Untuk |
|------|-----------|-------------|
| **bluegray** | Professional & modern | Presentasi bisnis, dokumentasi formal |
| **sketchy** | Hand-drawn style | Brainstorming, dokumentasi informal |
| **superhero** | Dark mode | Presentasi modern, dark theme lovers |
| **materia** | Material design | Clean & modern documentation |
| **vibrant** | Colorful & bright | Eye-catching presentations |
| **cerulean** | Clean & bright | Professional documentation |
| **hacker** | Terminal style | Technical documentation |
| **toy** | Playful style | Fun presentations |

### Custom Styling (Advanced)

Jika ingin custom warna sendiri, uncomment bagian `CUSTOM COLORS` di file dan sesuaikan warna:

```plantuml
skinparam actor {
    BackgroundColor #4A90E2    // Warna background
    BorderColor #2E5C8A        // Warna border
    FontColor #FFFFFF          // Warna text
}
```

**Catatan:** Jika menggunakan custom styling, pastikan tema built-in di-comment terlebih dahulu.

## Cara Menggunakan PlantUML

File `sequence-diagrams.puml` berisi 8 diagram sequence dalam format PlantUML yang dapat di-render dengan berbagai cara:

### 1. Online PlantUML Editor
- Buka: https://www.plantuml.com/plantuml/uml/
- Copy-paste kode dari file `.puml`
- Diagram akan otomatis ter-render

### 2. VS Code Extension
Install extension: **PlantUML** by jebbs
```
ext install jebbs.plantuml
```
Kemudian:
- Buka file `sequence-diagrams.puml`
- Tekan `Alt+D` untuk preview
- Atau klik kanan → "Preview Current Diagram"

### 3. Export ke Gambar
Dengan VS Code extension PlantUML:
- Klik kanan pada diagram
- Pilih "Export Current Diagram"
- Pilih format: PNG, SVG, atau PDF

### 4. Command Line (dengan Java & Graphviz)
```bash
# Install PlantUML
# Download plantuml.jar dari https://plantuml.com/download

# Generate PNG
java -jar plantuml.jar sequence-diagrams.puml

# Generate SVG
java -jar plantuml.jar -tsvg sequence-diagrams.puml
```

## Daftar Diagram

### Sequence Diagram (sequence-diagrams.puml)

1. **Login** - Alur autentikasi user
2. **Logout** - Alur logout user
3. **Pengajuan_Peminjaman** - Operator membuat pengajuan peminjaman
4. **Persetujuan_Peminjaman** - Kabag Umum menyetujui/menolak pengajuan
5. **Penyerahan_Barang** - Operator menyerahkan barang yang sudah disetujui
6. **Pengembalian_Barang** - Operator memproses pengembalian barang
7. **Manajemen_Barang** - Admin mengelola data barang (CRUD)
8. **Notifikasi** - Sistem notifikasi real-time
9. **Generate_Laporan** - Admin/Kabag membuat dan export laporan

### Activity Diagram

**activity-diagrams.puml** (Detail dengan partition & fork/repeat):
1. **Activity_Login** - Proses login user
2. **Activity_Pengajuan_Peminjaman** - Proses pengajuan peminjaman
3. **Activity_Persetujuan_Peminjaman** - Proses persetujuan/penolakan
4. **Activity_Penyerahan_Barang** - Proses penyerahan barang
5. **Activity_Pengembalian_Barang** - Proses pengembalian barang
6. **Activity_Manajemen_Barang** - Proses CRUD barang
7. **Activity_Generate_Laporan** - Proses generate dan export laporan
8. **Activity_Notifikasi** - Proses notifikasi real-time
9. **Activity_Manajemen_User** - Proses CRUD user (Admin)
10. **Activity_Alur_Lengkap_Peminjaman** - Alur lengkap dari pengajuan sampai pengembalian

**activity-diagrams-simple.puml** (Simple dengan partition):
1. **Activity_Login** - Login user
2. **Activity_Manajemen_User** - CRUD user
3. **Activity_Manajemen_Barang** - CRUD barang
4. **Activity_Pengajuan_Peminjaman** - Pengajuan peminjaman
5. **Activity_Persetujuan_Peminjaman** - Persetujuan/penolakan
6. **Activity_Penyerahan_Barang** - Penyerahan barang
7. **Activity_Pengembalian_Barang** - Pengembalian barang
8. **Activity_Generate_Laporan** - Generate laporan
9. **Activity_Notifikasi** - Notifikasi
10. **Activity_Alur_Lengkap** - Alur lengkap peminjaman

**activity-diagrams-classic.puml** (Classic tanpa partition - seperti contoh):
1. **Activity_Login_Logout** - Login dan logout
2. **Activity_Manajemen_User** - CRUD user
3. **Activity_Manajemen_Barang** - CRUD barang
4. **Activity_Pengajuan_Peminjaman** - Pengajuan peminjaman
5. **Activity_Persetujuan_Peminjaman** - Persetujuan/penolakan
6. **Activity_Penyerahan_Barang** - Penyerahan barang
7. **Activity_Pengembalian_Barang** - Pengembalian barang
8. **Activity_Generate_Laporan** - Generate laporan
9. **Activity_Alur_Lengkap** - Alur lengkap peminjaman
10. **Activity_Notifikasi** - Notifikasi

### Class Diagram (class-diagram.puml)

1. **Class_Diagram** - Class diagram lengkap dengan semua entitas, atribut, method, dan relasi
2. **Class_Diagram_Controllers** - Class diagram untuk semua controllers dan inheritance
3. **Class_Diagram_Simple** - Class diagram sederhana (simplified) untuk overview

## Keterangan Role & Akses

### Administrator
- Manajemen pengguna (CRUD)
- Manajemen barang inventaris (CRUD)
- Lihat semua peminjaman & pengembalian
- Generate & export laporan

### Operator
- Buat pengajuan peminjaman
- Serahkan barang yang sudah disetujui
- Proses pengembalian barang
- Lihat peminjaman yang dibuatnya

### Kepala Bagian Umum
- Setujui/tolak pengajuan peminjaman
- Lihat semua peminjaman & pengembalian
- Generate & export laporan

## Status Peminjaman

1. **pending** - Menunggu persetujuan Kabag Umum
2. **approved** - Disetujui, menunggu penyerahan barang
3. **rejected** - Ditolak oleh Kabag Umum
4. **dipinjam** - Barang sudah diserahkan ke peminjam
5. **selesai** - Barang sudah dikembalikan

## Komponen Sistem

### Controllers
- `AuthController` - Autentikasi & logout
- `PeminjamanController` - Pengajuan peminjaman
- `PersetujuanController` - Persetujuan & penyerahan barang
- `PengembalianController` - Pengembalian barang
- `BarangController` - Manajemen barang
- `NotifikasiController` - Notifikasi
- `LaporanController` - Generate laporan

### Models
- `User` - Data pengguna
- `Barang` - Data inventaris barang
- `Peminjaman` - Transaksi peminjaman
- `DetailPeminjaman` - Detail barang yang dipinjam
- `Notifikasi` - Notifikasi sistem
- `LogAktivitas` - Audit log

### Database Tables
- `users` - Data pengguna sistem
- `barang` - Data inventaris barang
- `peminjaman` - Transaksi peminjaman
- `detail_peminjaman` - Detail barang yang dipinjam
- `notifikasi` - Notifikasi pengguna
- `log_aktivitas` - Audit log aktivitas sistem
