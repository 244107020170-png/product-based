<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_redirects_to_choose_role_when_role_is_missing(): void
    {
        $response = $this->get('/register');

        $response->assertRedirect(route('choose.role'));
    }

    public function test_choose_role_screen_can_be_rendered(): void
    {
        $response = $this->get(route('choose.role'));

        $response
            ->assertOk()
            ->assertSeeText('Kamu daftar sebagai apa nih?');
    }

    public function test_registration_screen_can_be_rendered_for_a_selected_role(): void
    {
        $response = $this->get(route('register', ['role' => 'owner']));

        $response
            ->assertOk()
            ->assertSeeText('Daftar')
            ->assertSee('name="role" value="owner"', false);
    }

    public function test_new_players_can_register(): void
    {
        $response = $this->post(route('register', ['role' => 'player']), [
            'name' => 'Test User',
            'username' => 'test_user',
            'email' => 'test@example.com',
            'role' => 'player',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'player',
        ]);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_new_owners_can_register(): void
    {
        $response = $this->post(route('register', ['role' => 'owner']), [
            'name' => 'Owner User',
            'username' => 'owner_user',
            'email' => 'owner@example.com',
            'role' => 'owner',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'owner@example.com',
            'role' => 'owner',
        ]);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_usernames_must_be_unique_when_registering(): void
    {
        User::factory()->create([
            'username' => 'test_user',
        ]);

        $response = $this->from(route('register', ['role' => 'player']))->post(route('register', ['role' => 'player']), [
            'name' => 'Another User',
            'username' => 'test_user',
            'email' => 'another@example.com',
            'role' => 'player',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertSessionHasErrors('username')
            ->assertRedirect(route('register', ['role' => 'player']));
    }
}
