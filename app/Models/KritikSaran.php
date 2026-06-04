<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KritikSaran extends Model
{
    protected $table = 'kritik_saran';

    protected $fillable = [
        'id_user',
        'judul',
        'isi_kritik',
        'kategori',
        'status',
        'tanggapan',
        'id_petugas',
        'tanggapan_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggapan_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'id_petugas');
    }
}
