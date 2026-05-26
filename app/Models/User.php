<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
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
        'cover_photo',
        'phone',
        'bio',
        'sport_preference',
        'points',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Default attribute values for model.
     * Ensures `points` is 0 when not provided.
     */
    protected $attributes = [
        'points' => 0,
    ];
    /**
     * Model casts
     *
     * Use property $casts so Eloquent applies proper casting.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'points'            => 'integer',
    ];

    /**
     * Ensure default values on model creation.
     */
    protected static function booted(): void
    {
        static::creating(function (self $user) {
            if ($user->points === null) {
                $user->points = 0;
            }
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'admin' && $this->role === 'admin';
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

    /** Field milik owner */
    public function fields()
    {
        return $this->hasMany(Field::class, 'owner_id');
    }

    /** Favorite user ini */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /** Review user ini */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /** MatchPlayer entries */
    public function matchPlayers()
    {
        return $this->hasMany(MatchPlayer::class);
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
        if ($count >= 5)  return 'Active Player';
        return 'Beginner';
    }

    /** Helper: badge label Bahasa Indonesia */
    public function playerBadgeId(): string
    {
        return match($this->playerBadge()) {
            'Pro Player'    => 'Pemain Pro',
            'Active Player' => 'Pemain Aktif',
            default         => 'Pemula',
        };
    }

    /** Sport tags sebagai array */
    public function sportTags(): array
    {
        if (!$this->sport_preference) return [];
        return array_filter(array_map('trim', explode(',', $this->sport_preference)));
    }

    /** Helper: Get next tier target points */
    public function nextTierTarget(): int
    {
        $currentPoints = $this->points ?? 0;

        if ($currentPoints < 5)   return 5;
        if ($currentPoints < 20)  return 20;
        return 20; // Already at max tier
    }

    /** Helper: Get points needed to reach next tier */
    public function pointsNeeded(): int
    {
        $target = $this->nextTierTarget();
        $current = $this->points ?? 0;
        
        return max(0, $target - $current);
    }

    /** Helper: Get tier name based on points (English key) */
    public function tierName(): string
    {
        $currentPoints = $this->points ?? 0;

        if ($currentPoints >= 20) return 'Pro';
        if ($currentPoints >= 5)  return 'Active';
        return 'Beginner';
    }

    /** Helper: Get tier name in Bahasa Indonesia */
    public function tierNameId(): string
    {
        return match($this->tierName()) {
            'Pro'      => 'Pro',
            'Active'   => 'Aktif',
            default    => 'Pemula',
        };
    }

    /** Helper: Get tier color */
    public function tierColor(): string
    {
        return match($this->tierName()) {
            'Pro'      => '#7c3aed',
            'Active'   => '#1d6fcf',
            default    => '#6b7280',
        };
    }

    /** Helper: Get progress percentage */
    public function progressPercentage(): float
    {
        $target = $this->nextTierTarget();
        $current = $this->points ?? 0;
        $previousTarget = match(true) {
            $target == 5  => 0,
            $target == 20 => 5,
            default => 20,
        };
        
        $totalInTier = $target - $previousTarget;
        $progressInTier = $current - $previousTarget;
        
        if ($totalInTier <= 0) return 100;
        return min(100, ($progressInTier / $totalInTier) * 100);
    }
}
