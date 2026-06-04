<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'id_user',
        'aksi',
        'tipe',
        'deskripsi',
        'auditable_id',
        'auditable_type',
        'ip_address',
        'user_agent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function auditable()
    {
        return $this->morphTo();
    }

    public static function log(string $aksi, ?string $deskripsi = null, ?Model $auditable = null, ?string $tipe = null): self
    {
        return static::create([
            'id_user' => auth()->id(),
            'aksi' => $aksi,
            'tipe' => $tipe,
            'deskripsi' => $deskripsi,
            'auditable_id' => $auditable?->getKey(),
            'auditable_type' => $auditable?->getMorphClass(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
