<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PointsDefaultTest extends TestCase
{
    use RefreshDatabase;

    public function test_points_default_to_zero_on_create()
    {
        $data = [
            'name' => 'Points Test',
            'username' => 'points_test',
            'email' => 'points.test@example.com',
            'phone' => '081200000000',
            'role' => 'player',
            'gender' => 'laki-laki',
            'password' => Hash::make('password'),
            'sport_preference' => 'futsal',
        ];

        $user = User::create($data);

        $this->assertEquals(0, $user->points);
        $this->assertDatabaseHas('users', [
            'email' => 'points.test@example.com',
            'points' => 0,
        ]);
    }
}
