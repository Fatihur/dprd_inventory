<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'link',
        'dibaca_pada',
    ];

    protected $casts = [
        'dibaca_pada' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sudahDibaca(): bool
    {
        return $this->dibaca_pada !== null;
    }

    public function tandaiDibaca()
    {
        $this->update(['dibaca_pada' => now()]);
    }

    public static function kirim($userId, $judul, $pesan, $tipe = 'info', $link = null)
    {
        return self::create([
            'user_id' => $userId,
            'judul' => $judul,
            'pesan' => $pesan,
            'tipe' => $tipe,
            'link' => $link,
        ]);
    }

    public static function kirimKeRole($role, $judul, $pesan, $tipe = 'info', $link = null)
    {
        $users = User::where('role', $role)->get();
        foreach ($users as $user) {
            self::kirim($user->id, $judul, $pesan, $tipe, $link);
        }
    }

    public static function kirimKeBanyakRole($roles, $judul, $pesan, $tipe = 'info', $link = null)
    {
        $users = User::whereIn('role', $roles)->get();
        foreach ($users as $user) {
            self::kirim($user->id, $judul, $pesan, $tipe, $link);
        }
    }

    public function getIconAttribute(): string
    {
        return match($this->tipe) {
            'success' => 'bi-check-circle-fill text-success',
            'warning' => 'bi-exclamation-triangle-fill text-warning',
            'danger' => 'bi-x-circle-fill text-danger',
            default => 'bi-info-circle-fill text-info'
        };
    }
}
