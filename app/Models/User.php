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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
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

    /** Helper: badge berdasarkan jumlah match (Bahasa Indonesia) */
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

        if ($currentPoints < 20)  return 20;
        if ($currentPoints < 50)  return 50;
        if ($currentPoints < 100) return 100;
        return 100; // Already at max tier
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

        if ($currentPoints >= 100) return 'Champion';
        if ($currentPoints >= 50)  return 'Master';
        if ($currentPoints >= 20)  return 'Pro';
        return 'Beginner';
    }

    /** Helper: Get tier name in Bahasa Indonesia */
    public function tierNameId(): string
    {
        return match($this->tierName()) {
            'Champion' => 'Juara',
            'Master'   => 'Master',
            'Pro'      => 'Pro',
            default    => 'Pemula',
        };
    }

    /** Helper: Get tier color */
    public function tierColor(): string
    {
        return match($this->tierName()) {
            'Champion' => '#fbbf24',
            'Master'   => '#7c3aed',
            'Pro'      => '#1d6fcf',
            default    => '#6b7280',
        };
    }

    /** Helper: Get progress percentage */
    public function progressPercentage(): float
    {
        $target = $this->nextTierTarget();
        $current = $this->points ?? 0;
        $previousTarget = match(true) {
            $target == 20 => 0,
            $target == 50 => 20,
            $target == 100 => 50,
            default => 100,
        };
        
        $totalInTier = $target - $previousTarget;
        $progressInTier = $current - $previousTarget;
        
        return min(100, ($progressInTier / $totalInTier) * 100);
    }
}
