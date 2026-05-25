<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
    }

    public function test_owner_cannot_access_admin_dashboard()
    {
        $owner = User::factory()->create(['role' => 'owner']);

        $response = $this->actingAs($owner)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_player_cannot_access_admin_dashboard()
    {
        $player = User::factory()->create(['role' => 'player']);

        $response = $this->actingAs($player)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_guest_redirected_to_login()
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }
}
