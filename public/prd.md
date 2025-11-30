Baik, berikut **PRD yang sudah direvisi**, mengganti seluruh peran **“Pimpinan” menjadi “Kepala Bagian Umum”** tanpa mengubah struktur dan konsistensi dokumen.

---

# **📘 PRODUCT REQUIREMENTS DOCUMENT (PRD)**

## **Sistem Informasi Peminjaman & Pengembalian Inventaris Barang**

**Bagian Umum – Sekretariat DPRD Kabupaten Sumbawa**
**Teknologi:** Laravel 12, MySQL

---

# **1. Pendahuluan**

## **1.1 Latar Belakang**

Bagian Umum Sekretariat DPRD Kabupaten Sumbawa membutuhkan sistem untuk mengelola peminjaman dan pengembalian inventaris secara terstruktur. Sistem manual menyulitkan pelacakan stok, riwayat penggunaan, dan persetujuan peminjaman.

## **1.2 Tujuan**

* Mengotomatisasi proses peminjaman & pengembalian barang.
* Memudahkan pengelolaan inventaris.
* Menyediakan laporan untuk Kepala Bagian Umum & Admin.
* Meningkatkan akurasi data dan efisiensi kerja.

## **1.3 Pengguna Sistem**

1. **Admin**
2. **Operator**
3. **Kepala Bagian Umum**

---

# **2. Ruang Lingkup Sistem**

Meliputi:

* Manajemen user & role.
* Manajemen inventaris barang.
* Pengajuan peminjaman.
* Persetujuan peminjaman oleh Kepala Bagian Umum.
* Pengembalian barang.
* Pelaporan & monitoring stok.

---

# **3. Kebutuhan Fungsional**

## **3.1 Role: Admin**

### **F1. Login**

### **F2. Manajemen Pengguna**

* CRUD akun pengguna (Admin/Operator/Kepala Bagian Umum).

### **F3. Manajemen Data Barang**

* Tambah, ubah, hapus barang.
* Monitoring stok & kondisi barang.

### **F4. Laporan**

* Laporan peminjaman.
* Laporan pengembalian.
* Laporan stok barang.
* Export & print PDF.

### **F5. Logout**

---

## **3.2 Role: Operator**

### **F6. Login**

### **F7. Pengajuan Peminjaman**

* Memilih barang & jumlah.
* Mengisi data peminjam dan unit kerja.
* Status awal: **Menunggu persetujuan Kepala Bagian Umum**.

### **F8. Pengembalian Barang**

* Mengisi tanggal kembali & kondisi barang.
* Mengembalikan stok.

### **F9. Melihat Status**

* Status: pending, disetujui, ditolak, dipinjam, selesai.

### **F10. Logout**

---

## **3.3 Role: Kepala Bagian Umum**

### **F11. Login**

### **F12. Persetujuan Peminjaman**

* Melihat seluruh permohonan peminjaman.
* Menyetujui atau menolak.
* Jika menolak → memasukkan alasan penolakan.

### **F13. Melihat Laporan**

* Laporan peminjaman.
* Laporan pengembalian.
* Stok inventaris barang.

### **F14. Logout**

---

# **4. Kebutuhan Non-Fungsional**

* Keamanan password (bcrypt).
* Role-based access control (RBAC).
* UI responsive.
* Waktu loading < 3 detik.
* Audit log perubahan data penting.

---

# **5. Alur Proses**

## **5.1 Peminjaman**

1. Operator login.
2. Operator mengajukan peminjaman barang.
3. Sistem mencatat transaksi (status pending).
4. Kepala Bagian Umum login.
5. Kepala Bagian Umum setujui/tolak permohonan.
6. Jika disetujui → barang diserahkan, stok berkurang.

## **5.2 Pengembalian**

1. Operator membuka transaksi peminjaman aktif.
2. Mengisi detail pengembalian & kondisi barang.
3. Sistem memperbarui stok & status transaksi menjadi selesai.

---

# **6. Struktur Database (Revisi)**

## **6.1 Tabel `users`**

| Field | Type                              | Keterangan    |
| ----- | --------------------------------- | ------------- |
| role  | ENUM(admin, operator, kabag_umum) | (revisi role) |

## **6.2 Tabel `peminjaman`**

| Field            | Type                                                 | Keterangan                            |
| ---------------- | ---------------------------------------------------- | ------------------------------------- |
| kepala_bagian_id | INT                                                  | FK ke tabel users (role = kabag_umum) |
| alasan_penolakan | TEXT                                                 | nullable                              |
| status           | ENUM(pending, approved, rejected, dipinjam, selesai) |                                       |

(Tabel barang & detail_peminjaman tidak berubah)

---

# **7. Tampilan (UI/UX) – High Level**

* Dashboard khusus per role
* Menu barang
* Menu peminjaman
* Menu pengembalian
* Menu persetujuan (khusus Kepala Bagian Umum)
* Menu laporan

---

# **8. Risiko & Mitigasi**

| Risiko             | Mitigasi                           |
| ------------------ | ---------------------------------- |
| Stok tidak sinkron | Transactional DB dan validasi stok |
| Data hilang        | Backup DB terjadwal                |
| Salah input        | Validasi form dan notifikasi       |

---

# **9. Kriteria Keberhasilan**

* Persetujuan peminjaman hanya oleh Kepala Bagian Umum.
* Data transaksi tercatat baik & dapat ditelusuri.
* Laporan akurat dan bisa diekspor.
* Sistem stabil dan mudah dipakai.

