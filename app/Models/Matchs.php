<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matchs extends Model
{
    public function players()
{
    return $this->belongsToMany(User::class, 'match_players');
}
}
