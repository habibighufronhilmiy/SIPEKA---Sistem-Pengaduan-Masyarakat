<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $fillable = [
        'id_user',
        'id_kategori',
        'kode_tracking',
        'judul',
        'isi_laporan',
        'lokasi',
        'latitude',
        'longitude',
        'status',
        'id_petugas',
        'draft',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function media()
    {
        return $this->hasMany(MediaPengaduan::class, 'id_pengaduan');
    }

    public function riwayats()
    {
        return $this->hasMany(RiwayatPengaduan::class, 'id_pengaduan');
    }

    public function tanggapans()
    {
        return $this->hasMany(Tanggapan::class, 'id_pengaduan');
    }

    public function rating()
    {
        return $this->hasOne(Rating::class, 'id_pengaduan');
    }

    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_pengaduan');
    }
}
