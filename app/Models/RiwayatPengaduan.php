<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPengaduan extends Model
{
    protected $fillable = [
        'id_pengaduan',
        'status',
        'keterangan',
    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'id_pengaduan');
    }
}
