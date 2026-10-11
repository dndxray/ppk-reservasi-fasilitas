<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'nim_nip',
    'no_telepon',
    'status_verifikasi',
    'jenis_pengguna',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Samakan format nomor telepon: 0812..., 62812..., +62812... -> 0812...
     * Dipakai sebelum validasi unique supaya nomor yang sama tidak lolos
     * hanya karena penulisannya berbeda.
     */
    public static function normalisasiTelepon(?string $nomor): ?string
    {
        if ($nomor === null) {
            return null;
        }

        $nomor = preg_replace('/[^0-9+]/', '', trim($nomor));

        if ($nomor === '') {
            return null;
        }

        if (str_starts_with($nomor, '+62')) {
            return '0' . substr($nomor, 3);
        }

        if (str_starts_with($nomor, '62')) {
            return '0' . substr($nomor, 2);
        }

        return $nomor;
    }

    /**
     * Setiap nomor telepon yang disimpan otomatis dinormalisasi.
     */
    protected function noTelepon(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => self::normalisasiTelepon($value),
        );
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPetugas(): bool
    {
        return $this->role === 'petugas';
    }

    public function isPengguna(): bool
    {
        return $this->role === 'pengguna';
    }

    public function isVerifikasi(): bool
    {
        return $this->status_verifikasi === 'terverifikasi';
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    public function reservasiAktif()
    {
        return $this->reservations()
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->whereDate('tanggal', '>=', now()->toDateString());
    }
}