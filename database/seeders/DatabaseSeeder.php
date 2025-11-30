<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Barang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@dprd.go.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create Operator
        User::create([
            'name' => 'Operator Bagian Umum',
            'email' => 'operator@dprd.go.id',
            'password' => Hash::make('password'),
            'role' => 'operator',
        ]);

        // Create Kabag Umum
        User::create([
            'name' => 'Kepala Bagian Umum',
            'email' => 'kabag@dprd.go.id',
            'password' => Hash::make('password'),
            'role' => 'kabag_umum',
        ]);

        // Create sample barang
        $barangList = [
            ['nama_barang' => 'Laptop ASUS', 'kategori' => 'Elektronik', 'satuan' => 'Unit', 'stok' => 10, 'kondisi' => 'baik', 'lokasi' => 'Gudang A'],
            ['nama_barang' => 'Proyektor Epson', 'kategori' => 'Elektronik', 'satuan' => 'Unit', 'stok' => 5, 'kondisi' => 'baik', 'lokasi' => 'Gudang A'],
            ['nama_barang' => 'Meja Rapat', 'kategori' => 'Furniture', 'satuan' => 'Buah', 'stok' => 20, 'kondisi' => 'baik', 'lokasi' => 'Gudang B'],
            ['nama_barang' => 'Kursi Lipat', 'kategori' => 'Furniture', 'satuan' => 'Buah', 'stok' => 50, 'kondisi' => 'baik', 'lokasi' => 'Gudang B'],
            ['nama_barang' => 'Microphone Wireless', 'kategori' => 'Elektronik', 'satuan' => 'Set', 'stok' => 8, 'kondisi' => 'baik', 'lokasi' => 'Gudang A'],
            ['nama_barang' => 'Sound System', 'kategori' => 'Elektronik', 'satuan' => 'Set', 'stok' => 3, 'kondisi' => 'baik', 'lokasi' => 'Gudang A'],
            ['nama_barang' => 'Papan Tulis', 'kategori' => 'ATK', 'satuan' => 'Buah', 'stok' => 10, 'kondisi' => 'baik', 'lokasi' => 'Gudang C'],
            ['nama_barang' => 'Tenda Rapat', 'kategori' => 'Perlengkapan', 'satuan' => 'Unit', 'stok' => 5, 'kondisi' => 'baik', 'lokasi' => 'Gudang D'],
            ['nama_barang' => 'Kamera DSLR', 'kategori' => 'Elektronik', 'satuan' => 'Unit', 'stok' => 2, 'kondisi' => 'baik', 'lokasi' => 'Gudang A'],
            ['nama_barang' => 'Printer Epson', 'kategori' => 'Elektronik', 'satuan' => 'Unit', 'stok' => 4, 'kondisi' => 'baik', 'lokasi' => 'Gudang A'],
        ];

        foreach ($barangList as $item) {
            $item['kode_barang'] = Barang::generateKode();
            Barang::create($item);
        }
    }
}
