<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matchs extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'title',
        'sport',
        'field_id',
        'date',
        'time',
        'max_player',
        'created_by',
        'type', // public / private
    ];

    /** Lapangan */
    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    /** Host (pembuat) */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Semua player yang join (termasuk unpaid) */
    public function players()
    {
        return $this->belongsToMany(User::class, 'match_players', 'match_id', 'user_id');
    }

    /** Player yang sudah bayar */
    public function paidPlayers()
    {
        return $this->belongsToMany(User::class, 'match_players', 'match_id', 'user_id')
            ->wherePivot('payment_status', \App\Enums\PaymentStatus::PAID);
    }

    public function participantEntries()
    {
        return $this->hasMany(MatchPlayer::class, 'match_id');
    }

    /** Harga per jam setelah diskon (jika ada promo aktif) */
    public function getEffectivePricePerHourAttribute(): int
    {
        if (!$this->field) return 0;
        return $this->field->promo_price_raw ?? $this->field->price_per_hour;
    }

    public function getTotalCostAttribute(): int
    {
        if (! $this->field) return 0;
        return $this->effective_price_per_hour * 2;
    }

    public function getContributionPerPlayerAttribute(): int
    {
        if (! $this->field || $this->max_player < 1) return 0;
        return (int) round(($this->effective_price_per_hour * 2) / $this->max_player);
    }

    public function isPublic(): bool
    {
        return $this->type === 'public';
    }

    /** Waktu dalam format HH:MM – HH:MM (end = start + 2h) */
    public function timeRange(): string
    {
        $start = \Carbon\Carbon::createFromFormat('H:i:s', $this->time);
        $end   = $start->copy()->addHours(2);
        return $start->format('H:i') . ' - ' . $end->format('H:i');
    }

    /** Tanggal dalam format Indonesia */
    public function formattedDate(): string
    {
        return \Carbon\Carbon::parse($this->date)
            ->locale('id')
            ->translatedFormat('j F Y');
    }
}
