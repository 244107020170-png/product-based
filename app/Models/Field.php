<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Field extends Model
{
    protected $appends = ['image_url', 'fallback_image', 'has_active_promo', 'promo_price', 'promo_badge', 'promo_price_raw', 'promo_start', 'promo_end'];

    protected $fillable = [
        'name',
        'description',
        'type',
        'location',
        'maps_link',
        'price_per_hour',
        'open_time',
        'close_time',
        'owner_id',
        'image',
        'facilities',
        'is_available',
        'featured',
        'number_of_courts',
        'verification_status',
        'verification_notes',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'facilities' => 'array',
        'rating' => 'float',
        'verified_at' => 'datetime',
        'is_available' => 'boolean',
        'featured' => 'boolean',
        'number_of_courts' => 'integer',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function matches()
    {
        return $this->hasMany(Matchs::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }

    public function discounts()
    {
        return $this->belongsToMany(Discount::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public static function recalculateStats(int $fieldId): void
    {
        $field = static::find($fieldId);
        if (!$field) return;
        $avg = Review::where('field_id', $fieldId)->avg('rating');
        $count = Review::where('field_id', $fieldId)->count();
        $field->rating = round($avg ?? 0, 1);
        $field->review_count = $count;
        $field->save();
    }

    /**
     * Get the fallback sport icon URL based on field type.
     * Always returns a valid asset path (committed in repo).
     */
    public function getFallbackImageAttribute(): string
    {
        $typeLower = $this->type ? strtolower(trim($this->type)) : '';

        $sportImages = [
            'futsal'    => 'futsal.svg',
            'badminton' => 'badminton.svg',
            'basket'    => 'basket.svg',
            'voli'      => 'volley.svg',
            'volley'    => 'volley.svg',
            'tennis'    => 'tennis.svg',
            'tenis'     => 'tennis.svg',
            'golf'      => 'golf.svg',
            'renang'    => 'renang.svg',
            'panahan'   => 'panahan.svg',
            'lari'      => 'lari.svg',
            'sepeda'    => 'sepeda.svg',
            'tinju'     => 'tinju.svg',
            'bela diri' => 'bela-diri.svg',
            'yoga'      => 'yoga.svg',
            'fitness'   => 'fitness.svg',
            'hiking'    => 'hiking.svg',
            'padel'     => 'padel.svg',
            'baseball'  => 'baseball.svg',
            'rugby'     => 'rugby.svg',
            'senam'     => 'senam.svg',
        ];

        $file = $sportImages[$typeLower] ?? 'default.svg';

        return asset('assets/images/sports/' . $file);
    }

    /**
     * Get image URL with file existence check.
     * If uploaded file is missing (e.g. lost after redeploy),
     * falls back to sport icon from public/assets.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        return $this->fallback_image;
    }

    public function formattedPrice(): string
    {
        return 'Rp' . number_format($this->price_per_hour, 0, ',', '.') . '/jam';
    }

    public function hasActivePromo(): bool
    {
        return $this->discounts()->active()->exists();
    }

    public function getHasActivePromoAttribute(): bool
    {
        return $this->hasActivePromo();
    }

    public function getActivePromoAttribute()
    {
        return $this->discounts()->active()->orderBy('value', 'desc')->first();
    }

    public function getPromoPriceAttribute(): ?string
    {
        $promo = $this->activePromo;
        if (!$promo) return null;

        $discounted = $this->calculateDiscountedPrice($this->price_per_hour, $promo);
        return 'Rp' . number_format($discounted, 0, ',', '.') . '/jam';
    }

    public function getPromoPriceRawAttribute(): ?int
    {
        $promo = $this->activePromo;
        if (!$promo) return null;

        return $this->calculateDiscountedPrice($this->price_per_hour, $promo);
    }

    public function getPromoBadgeAttribute(): ?string
    {
        $promo = $this->activePromo;
        if (!$promo) return null;

        if ($promo->type === 'percentage') {
            return 'Diskon ' . (int) $promo->value . '%';
        }
        return 'Diskon Rp' . number_format((int) $promo->value, 0, ',', '.');
    }

    public function getPromoStartAttribute(): ?string
    {
        $promo = $this->activePromo;
        if (!$promo) return null;

        return $promo->start_date->format('d M Y');
    }

    public function getPromoEndAttribute(): ?string
    {
        $promo = $this->activePromo;
        if (!$promo) return null;

        return $promo->end_date->format('d M Y');
    }

    private function calculateDiscountedPrice(int $price, Discount $promo): int
    {
        if ($promo->type === 'percentage') {
            return (int) round($price * (1 - $promo->value / 100));
        }
        return max(0, $price - (int) $promo->value);
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
            'Rumput Premium' => '✓',
            'Mushala' => '✓',
            'Toilet Bersih' => '✓',
            'Kursi' => '✓',
            'Parkir Luas' => '✓',
            'LED Tuning' => '✓',
            'Kantin' => '✓',
            'Ruang Ganti' => '✓',
            'AC' => '✓',
            'WiFi' => '✓',
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
