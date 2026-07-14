<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleLoginTest extends TestCase
{
    use RefreshDatabase;

    private function fakeGoogleUser(string $email, string $id = 'google-123'): void
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn($id);
        $socialiteUser->shouldReceive('getEmail')->andReturn($email);
        $socialiteUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.png');

        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
    }

    public function test_existing_user_can_log_in_with_google_and_gets_linked(): void
    {
        $user = User::factory()->create(['email' => 'pablo@example.com', 'google_id' => null]);

        $this->fakeGoogleUser('pablo@example.com', 'google-abc');

        $this->get('/auth/google/callback')
            ->assertRedirect(route('admin.projects.index'));

        $this->assertAuthenticatedAs($user);
        $this->assertSame('google-abc', $user->fresh()->google_id);
    }

    public function test_unknown_google_account_is_rejected(): void
    {
        User::factory()->create(['email' => 'pablo@example.com']);

        $this->fakeGoogleUser('intruso@example.com');

        $this->get('/auth/google/callback')
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('google');

        $this->assertGuest();
    }
}
