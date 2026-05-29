<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\User;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    public function run(): void
    {
        // Get a user as owner (create one if doesn't exist)
        $owner = User::where('role', 'owner')->first() ?? User::factory()->create(['role' => 'owner']);

        $fieldsData = [
            [
                'name' => 'Lapangan Voli - Veteran Muda',
                'type' => 'Voli',
                'location' => 'Lowokwaru, Malang',
                'price_per_hour' => 120000,
                'image' => null,
                'facilities' => ['Rumput Premium', 'Mushala', 'Toilet Bersih', 'Kursi', 'Parkir Luas', 'LED Tuning'],
                'rating' => 4.8,
                'review_count' => 24,
            ],
            [
                'name' => 'Lapangan Basket - Central',
                'type' => 'Basket',
                'location' => 'Malang Pusat',
                'price_per_hour' => 150000,
                'image' => null,
                'facilities' => ['AC', 'WiFi', 'Kantin', 'Ruang Ganti', 'Parkir Luas'],
                'rating' => 4.6,
                'review_count' => 18,
            ],
            [
                'name' => 'Lapangan Tenis - Sport Complex',
                'type' => 'Tennis',
                'location' => 'Arjosari, Malang',
                'price_per_hour' => 100000,
                'image' => null,
                'facilities' => ['Rumput Premium', 'Kursi', 'Mushala', 'Toilet Bersih'],
                'rating' => 4.7,
                'review_count' => 32,
            ],
            [
                'name' => 'Lapangan Futsal - Victory',
                'type' => 'Futsal',
                'location' => 'Tunggulwulung, Malang',
                'price_per_hour' => 80000,
                'image' => null,
                'facilities' => ['LED Tuning', 'Kantin', 'Parkir Luas', 'WiFi', 'Ruang Ganti'],
                'rating' => 4.9,
                'review_count' => 45,
            ],
            [
                'name' => 'Lapangan Badminton - Langgeng',
                'type' => 'Badminton',
                'location' => 'Karangbesuki, Malang',
                'price_per_hour' => 90000,
                'image' => null,
                'facilities' => ['AC', 'LED Tuning', 'Mushala', 'Toilet Bersih', 'Parkir Luas'],
                'rating' => 4.5,
                'review_count' => 21,
            ],
            [
                'name' => 'Lapangan Voli - Graha Cakra',
                'type' => 'Voli',
                'location' => 'Batu, Malang',
                'price_per_hour' => 110000,
                'image' => null,
                'facilities' => ['Rumput Premium', 'Kantin', 'WiFi', 'Parkir Luas', 'Kursi'],
                'rating' => 4.4,
                'review_count' => 16,
            ],
        ];

        foreach ($fieldsData as $fieldData) {
            Field::create([
                'name' => $fieldData['name'],
                'type' => $fieldData['type'],
                'location' => $fieldData['location'],
                'price_per_hour' => $fieldData['price_per_hour'],
                'image' => $fieldData['image'],
                'facilities' => json_encode($fieldData['facilities']),
                'rating' => $fieldData['rating'],
                'review_count' => $fieldData['review_count'],
                'owner_id' => $owner->id,
                'description' => 'Lapangan berkualitas dengan fasilitas lengkap',
            ]);
        }
    }
}
