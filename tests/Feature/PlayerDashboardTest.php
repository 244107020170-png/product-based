<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlayerDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_player_dashboard_can_be_rendered(): void
    {
        $user = User::factory()->create([
            'role' => 'player',
            'gender' => 'perempuan',
            'avatar_profile' => 'profil2.png',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('dashboard'));

        $response
            ->assertOk()
            ->assertSee('Hi, Sport Enthusiast!')
            ->assertSee('Konfirmasi gagal')
            ->assertSee('Pesan lagi')
            ->assertSee('profil2.png');
    }
}
