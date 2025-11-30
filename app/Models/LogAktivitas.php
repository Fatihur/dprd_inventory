<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id',
        'aksi',
        'tabel',
        'record_id',
        'data_lama',
        'data_baru',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function catat($aksi, $tabel, $recordId = null, $dataLama = null, $dataBaru = null)
    {
        if (!auth()->check()) return;
        
        return self::create([
            'user_id' => auth()->id(),
            'aksi' => $aksi,
            'tabel' => $tabel,
            'record_id' => $recordId,
            'data_lama' => $dataLama ? json_encode($dataLama) : null,
            'data_baru' => $dataBaru ? json_encode($dataBaru) : null,
            'ip_address' => request()->ip(),
        ]);
    }
}
