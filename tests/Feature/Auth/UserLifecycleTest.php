<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

class UserLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_callback_creates_and_logs_in_user(): void
    {
        Socialite::fake('google', (new SocialiteUser())->setRaw([])->map([
            'id' => 'google-user-123',
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
        ]));

        $this->get(route('auth.google.callback'))
            ->assertRedirect('/search');

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'provider' => 'Google',
            'provider_id' => 'google-user-123',
            'name' => 'Ada Lovelace',
        ]);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->delete(route('profile.destroy'))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('landingpage.index'));

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }
}
