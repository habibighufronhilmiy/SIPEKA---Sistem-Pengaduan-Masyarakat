<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PilihanVoting extends Model
{
    protected $fillable = [
        'id_voting',
        'pilihan',
    ];

    public function voting()
    {
        return $this->belongsTo(Voting::class, 'id_voting');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'voting_user', 'id_pilihan', 'id_user')
            ->withPivot('id_voting')
            ->withTimestamps();
    }
}
