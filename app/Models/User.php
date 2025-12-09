<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Nama tabel di database
     * 
     * @var string
     */
    protected $table = 'users';

    /**
     * Atribut yang dapat diisi secara massal
     *
     * @var array<string>
     */
    protected $fillable = [
        'username',
        'password',
        'no_hp',
        'role',
        'is_banned',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Tipe casting untuk atribut
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
     * Relasi: User memiliki banyak review
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relasi: User memiliki banyak favorit
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
}
