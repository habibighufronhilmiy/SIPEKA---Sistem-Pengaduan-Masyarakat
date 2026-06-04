<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'telepon',
        'alamat',
        'role',
        'foto_profil',
        'social_id',
        'social_type',
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

    public function pengaduans()
    {
        return $this->hasMany(Pengaduan::class, 'id_user');
    }

    public function pengaduanPetugas()
    {
        return $this->hasMany(Pengaduan::class, 'id_petugas');
    }

    public function tanggapans()
    {
        return $this->hasMany(Tanggapan::class, 'id_petugas');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'id_user');
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_user');
    }

    public function votings()
    {
        return $this->hasMany(Voting::class, 'id_user');
    }

    public function kritikSarans()
    {
        return $this->hasMany(KritikSaran::class, 'id_user');
    }

    public function votingPilihans()
    {
        return $this->belongsToMany(PilihanVoting::class, 'voting_user', 'id_user', 'id_pilihan')
            ->withPivot('id_voting')
            ->withTimestamps();
    }
}
