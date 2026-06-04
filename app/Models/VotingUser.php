<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VotingUser extends Model
{
    protected $table = 'voting_user';

    protected $fillable = [
        'id_voting',
        'id_pilihan',
        'id_user',
    ];

    public function voting()
    {
        return $this->belongsTo(Voting::class, 'id_voting');
    }

    public function pilihan()
    {
        return $this->belongsTo(PilihanVoting::class, 'id_pilihan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
