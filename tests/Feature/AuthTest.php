<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function admin(array $attributes = []): User
    {
        $user = new User();
        $user->forceFill(array_merge([
            'firstname' => 'Test',
            'name' => 'Admin',
            'email' => 'admin@example.invalid',
            'password' => Hash::make('correct-horse'),
            'role' => 'admin',
        ], $attributes))->save();

        return $user;
    }

    public function testLoginPageRenders()
    {
        $this->get('/login')->assertOk()->assertSee('Anmelden')->assertSee('Passwort vergessen?');
    }

    public function testLoginLeadsToTheAdmin()
    {
        $user = $this->admin();

        $this->post('/login', ['email' => 'admin@example.invalid', 'password' => 'correct-horse'])
            ->assertRedirect('/administration');

        $this->assertAuthenticatedAs($user);
        $this->get('/administration')->assertOk();
    }

    public function testLoginReturnsToTheRequestedAdminPage()
    {
        $this->admin();

        $this->get('/administration/project')->assertRedirect('/login');
        $this->post('/login', ['email' => 'admin@example.invalid', 'password' => 'correct-horse'])
            ->assertRedirect('/administration/project');
    }

    public function testWrongPasswordIsRejected()
    {
        $this->admin();

        $this->from('/login')->post('/login', ['email' => 'admin@example.invalid', 'password' => 'wrong'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors(['email' => __('auth.failed')]);

        $this->assertGuest();
    }

    public function testLoginIsThrottledAfterFiveFailures()
    {
        $this->admin();

        foreach (range(1, 5) as $i) {
            $this->post('/login', ['email' => 'admin@example.invalid', 'password' => 'wrong']);
        }

        $this->post('/login', ['email' => 'admin@example.invalid', 'password' => 'correct-horse'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertStringContainsString('Zu viele Loginversuche', session('errors')->first('email'));
    }

    public function testNonAdminsCannotOpenTheAdmin()
    {
        $this->admin(['role' => 'editor']);

        $this->post('/login', ['email' => 'admin@example.invalid', 'password' => 'correct-horse']);
        $this->get('/administration')->assertForbidden();
    }

    public function testLogoutByGetAndPost()
    {
        $user = $this->admin();

        $this->actingAs($user)->get('/logout')->assertRedirect('/');
        $this->assertGuest();

        $this->actingAs($user)->post('/logout')->assertRedirect('/');
        $this->assertGuest();
    }

    public function testForgotPasswordSendsTheResetMail()
    {
        Notification::fake();
        $user = $this->admin();

        $this->get('/password/reset')->assertOk();
        $this->from('/password/reset')->post('/password/email', ['email' => 'admin@example.invalid'])
            ->assertRedirect('/password/reset')
            ->assertSessionHas('status');

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $url = $notification->toMail($user)->actionUrl;
            return str_contains($url, '/password/reset/' . $notification->token);
        });
    }

    public function testForgotPasswordForAnUnknownAddress()
    {
        Notification::fake();

        $this->from('/password/reset')->post('/password/email', ['email' => 'nobody@example.invalid'])
            ->assertSessionHasErrors('email');

        Notification::assertNothingSent();
    }

    public function testResetSetsTheNewPasswordAndLogsIn()
    {
        $user = $this->admin();
        $token = Password::createToken($user);

        $this->get("/password/reset/{$token}")->assertOk()->assertSee($token);

        $this->post('/password/reset', [
            'token' => $token,
            'email' => 'admin@example.invalid',
            'password' => 'battery-staple',
            'password_confirmation' => 'battery-staple',
        ])->assertRedirect('/administration');

        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('battery-staple', $user->fresh()->password));
    }

    public function testResetWithABadToken()
    {
        $this->admin();

        $this->from('/password/reset/nope')->post('/password/reset', [
            'token' => 'nope',
            'email' => 'admin@example.invalid',
            'password' => 'battery-staple',
            'password_confirmation' => 'battery-staple',
        ])->assertSessionHasErrors('email');

        $this->assertTrue(Hash::check('correct-horse', User::first()->password));
    }

    public function testRemovedRoutesAreGone()
    {
        $this->get('/register')->assertNotFound();
        $this->get('/email/verify')->assertNotFound();
        $this->get('/password/confirm')->assertNotFound();
    }
}
