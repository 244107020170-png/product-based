<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchPlayer extends Model
{
    protected $fillable = [
        'match_id',
        'user_id',
    ];

    public function match()
    {
        return $this->belongsTo(Matchs::class, 'match_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
