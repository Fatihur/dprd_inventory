<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOperator(): bool
    {
        return $this->role === 'operator';
    }

    public function isKabagUmum(): bool
    {
        return $this->role === 'kabag_umum';
    }

    public function peminjamanOperator()
    {
        return $this->hasMany(Peminjaman::class, 'operator_id');
    }

    public function peminjamanApproved()
    {
        return $this->hasMany(Peminjaman::class, 'kepala_bagian_id');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class);
    }

    public function getRoleNameAttribute(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'operator' => 'Operator',
            'kabag_umum' => 'Kepala Bagian Umum',
            default => $this->role
        };
    }
}
