<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaPengaduan extends Model
{
    protected $fillable = [
        'id_pengaduan',
        'file_path',
        'file_type',
    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'id_pengaduan');
    }
}
