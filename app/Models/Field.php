<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = [
        'name',
        'description',
        'location',
        'price_per_hour',
        'owner_id',
        'image',
        'facilities',
        'rating',
        'review_count',
    ];

    protected $casts = [
        'facilities' => 'array',
        'rating' => 'float',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get formatted price with currency
     */
    public function formattedPrice(): string
    {
        return 'Rp' . number_format($this->price_per_hour, 0, ',', '.') . '/jam';
    }

    /**
     * Get default facilities if none set
     */
    public function getFacilities(): array
    {
        if (!$this->facilities) {
            return [];
        }
        
        if (is_string($this->facilities)) {
            return json_decode($this->facilities, true) ?? [];
        }
        
        return $this->facilities;
    }

    /**
     * Get facilities with icons
     */
    public function getFacilitiesWithIcons(): array
    {
        $facilityIcons = [
            'Rumput Premium' => '🌱',
            'Mushala' => '🕌',
            'Toilet Bersih' => '🚽',
            'Kursi' => '🪑',
            'Parkir Luas' => '🅿️',
            'LED Tuning' => '💡',
            'Kantin' => '🍜',
            'Ruang Ganti' => '👕',
            'AC' => '❄️',
            'WiFi' => '📡',
        ];

        $facilities = $this->getFacilities();
        $result = [];

        foreach ($facilities as $facility) {
            $result[] = [
                'name' => $facility,
                'icon' => $facilityIcons[$facility] ?? '✓',
            ];
        }

        return $result;
    }
}

