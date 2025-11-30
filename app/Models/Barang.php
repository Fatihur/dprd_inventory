<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'deskripsi',
        'kategori',
        'satuan',
        'stok',
        'kondisi',
        'lokasi',
    ];

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function getKondisiLabelAttribute(): string
    {
        return match($this->kondisi) {
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            default => $this->kondisi
        };
    }

    public function getStokTersediaAttribute(): int
    {
        $dipinjam = $this->detailPeminjaman()
            ->whereHas('peminjaman', fn($q) => $q->whereIn('status', ['approved', 'dipinjam']))
            ->sum('jumlah');
        
        return max(0, $this->stok - $dipinjam);
    }

    public static function generateKode(): string
    {
        $prefix = 'BRG-' . date('Ym');
        $lastBarang = self::where('kode_barang', 'like', $prefix . '%')
            ->orderBy('kode_barang', 'desc')
            ->first();
        
        if ($lastBarang) {
            $lastNumber = (int) substr($lastBarang->kode_barang, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
