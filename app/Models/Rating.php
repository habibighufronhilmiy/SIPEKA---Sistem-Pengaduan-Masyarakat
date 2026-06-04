<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
        'id_pengaduan',
        'id_user',
        'rating',
        'komentar',
    ];

    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'id_pengaduan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
