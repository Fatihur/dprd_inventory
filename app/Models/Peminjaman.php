<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'kode_peminjaman',
        'operator_id',
        'kepala_bagian_id',
        'nama_peminjam',
        'email_peminjam',
        'no_hp_peminjam',
        'unit_kerja',
        'keperluan',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
        'alasan_penolakan',
        'catatan_pengembalian',
        'bukti_peminjaman',
        'bukti_pengembalian',
        'notifikasi_jatuh_tempo_dikirim',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali_rencana' => 'date',
        'tanggal_kembali_aktual' => 'date',
    ];

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function kepalaBagian()
    {
        return $this->belongsTo(User::class, 'kepala_bagian_id');
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'dipinjam' => 'Sedang Dipinjam',
            'selesai' => 'Selesai',
            default => $this->status
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'info',
            'rejected' => 'danger',
            'dipinjam' => 'primary',
            'selesai' => 'success',
            default => 'secondary'
        };
    }

    public static function generateKode(): string
    {
        $prefix = 'PJM-' . date('Ymd');
        $lastPeminjaman = self::where('kode_peminjaman', 'like', $prefix . '%')
            ->orderBy('kode_peminjaman', 'desc')
            ->first();
        
        if ($lastPeminjaman) {
            $lastNumber = (int) substr($lastPeminjaman->kode_peminjaman, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getBuktiPeminjamanUrlAttribute(): ?string
    {
        return $this->bukti_peminjaman ? asset('storage/' . $this->bukti_peminjaman) : null;
    }

    public function getBuktiPengembalianUrlAttribute(): ?string
    {
        return $this->bukti_pengembalian ? asset('storage/' . $this->bukti_pengembalian) : null;
    }
}
