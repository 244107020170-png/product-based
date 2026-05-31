<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Community extends Model
{
    protected $fillable = [
        'created_by',
        'name',
        'sport_category',
        'city',
        'description',
        'photo',
        'whatsapp_link',
        'instagram_link',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'community_members')
            ->withTimestamps();
    }

    public function memberCount(): int
    {
        return $this->members()->count();
    }
}
