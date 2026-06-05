<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tanggapan extends Model
{
    protected $fillable = [
        'id_pengaduan',
        'id_petugas',
        'id_user',
        'tgl_tanggapan',
        'isi_tanggapan',
        'bukti_foto',
    ];

    protected function casts(): array
    {
        return [
            'tgl_tanggapan' => 'datetime',
        ];
    }

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'id_pengaduan');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
