<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'gender',
        'avatar_profile',
        'phone',
        'bio',
        'sport_preference',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /* ---- Relasi ---- */

    /** Match yang dibuat oleh user ini */
    public function createdMatches()
    {
        return $this->hasMany(Matchs::class, 'created_by');
    }

    /** Match yang diikuti user ini (via match_players) */
    public function joinedMatches()
    {
        return $this->belongsToMany(Matchs::class, 'match_players', 'user_id', 'match_id');
    }

    /** Booking user ini */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /** Helper: URL avatar */
    public function avatarUrl(): string
    {
        if ($this->avatar_profile) {
            // Jika path lengkap (upload) gunakan storage, jika filename pakai characters/
            if (str_starts_with($this->avatar_profile, 'avatars/')) {
                return asset('storage/' . $this->avatar_profile);
            }
            return asset('assets/images/characters/' . $this->avatar_profile);
        }

        $default = $this->gender === 'perempuan' ? 'profil2.png' : 'profil1.png';
        return asset('assets/images/characters/' . $default);
    }

    /** Helper: badge berdasarkan jumlah match */
    public function playerBadge(): string
    {
        $count = $this->createdMatches()->count() + $this->joinedMatches()->count();

        if ($count >= 20) return 'Pro Player';
        if ($count >= 6)  return 'Active Player';
        return 'Beginner';
    }

    /** Sport tags sebagai array */
    public function sportTags(): array
    {
        if (!$this->sport_preference) return [];
        return array_filter(array_map('trim', explode(',', $this->sport_preference)));
    }
}
