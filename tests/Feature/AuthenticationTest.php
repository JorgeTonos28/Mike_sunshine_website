<?php

namespace Tests\Feature;

use App\Actions\Auth\ResolveGoogleUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as GoogleUser;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_local_login_rotates_session_and_rejects_inactive_users(): void
    {
        $user = User::factory()->create(['password' => 'StudioPassword123', 'is_active' => true]);
        $this->post('/login', ['email' => $user->email, 'password' => 'StudioPassword123'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
        $this->post('/logout')->assertRedirect('/login');
        $user->forceFill(['is_active' => false])->save();
        $this->post('/login', ['email' => $user->email, 'password' => 'StudioPassword123'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_local_login_is_throttled(): void
    {
        foreach (range(1, 5) as $attempt) {
            $this->post('/login', ['email' => 'throttled@example.test', 'password' => 'wrong'])->assertSessionHasErrors();
        }
        $this->post('/login', ['email' => 'throttled@example.test', 'password' => 'wrong'])->assertStatus(429);
    }

    public function test_verified_google_identity_signs_in_only_precreated_user(): void
    {
        $user = User::factory()->create(['email' => 'mike@example.test', 'role' => 'admin']);
        $identity = (new GoogleUser)->map(['id' => 'google-123', 'email' => $user->email]);
        $identity->setRaw(['email_verified' => true]);
        Socialite::shouldReceive('driver')->with('google')->andReturnSelf();
        Socialite::shouldReceive('user')->andReturn($identity);
        $this->get('/auth/google/callback')->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
        $this->assertSame('google-123', $user->fresh()->google_id);
    }

    public function test_google_does_not_register_unknown_addresses(): void
    {
        $identity = (new GoogleUser)->map(['id' => 'unknown', 'email' => 'unknown@example.test']);
        $identity->setRaw(['email_verified' => true]);
        try {
            app(ResolveGoogleUser::class)->handle($identity);
            $this->fail('Unknown identity was allowed');
        } catch (ValidationException) {
            $this->assertDatabaseCount('users', 0);
        }
    }

    public function test_google_rejects_unverified_email_or_conflicting_subject(): void
    {
        User::factory()->create(['email' => 'mike@example.test', 'google_id' => 'original']);
        foreach ([false, true] as $verified) {
            $identity = (new GoogleUser)->map(['id' => 'other-subject', 'email' => 'mike@example.test']);
            $identity->setRaw(['email_verified' => $verified]);
            try {
                app(ResolveGoogleUser::class)->handle($identity);
                $this->fail('Unverified/conflicting identity was allowed');
            } catch (ValidationException) {
                $this->assertSame('original', User::first()->google_id);
            }
        }
    }

    public function test_google_invalid_state_fails_closed_and_signup_is_absent(): void
    {
        Socialite::shouldReceive('driver')->andReturnSelf();
        Socialite::shouldReceive('user')->andThrow(new \Laravel\Socialite\Two\InvalidStateException);
        $this->get('/auth/google/callback')->assertRedirect('/login')->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->post('/register', [])->assertNotFound();
    }
}
